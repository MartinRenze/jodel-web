<?php
error_reporting(-1);
include 'php/DatabaseConnect.php';
include 'php/Requests/AbstractRequest.php';
include 'php/Requests/CreateUser.php';
include 'php/AccountData.php';
include 'php/Location.php';
include 'php/Requests/GetPosts.php';
include 'php/Requests/GetKarma.php';
include 'php/Requests/UpdateLocation.php';
include 'php/Requests/Upvote.php';
include 'php/Requests/Downvote.php';
include 'php/Requests/GetPostDetails.php';
include 'php/Requests/SendJodel.php';

require_once 'php/Requests/libary/Requests.php';
Requests::register_autoloader();

$lastPostId = '';

function isTokenFresh(Location $location)
{
	$db = new DatabaseConnect();  
	$result = $db->query("SELECT * FROM accounts WHERE id='1'");
	
	if ($result->num_rows > 0)
	{
			// output data of each row
			while($row = $result->fetch_assoc()) {
					//$access_token = $row["access_token"];
					$expiration_date = $row["expiration_date"];
					$deviceUid = $row["device_uid"];
					$access_token = $row["access_token"];
			}
	}
	else
	{
			echo '0 results';
	}

	if($expiration_date <= time()) {
		$accountCreator = new CreateUser();
		$accountCreator->setAccessToken($access_token);//$accountData->getAccessToken());
		$accountCreator->setDeviceUid($deviceUid);
		$accountCreator->setLocation($location);
		$data = $accountCreator->execute();

		$access_token = (string)$data[0]['access_token'];
		$expiration_date = $data[0]['expiration_date'];
		$device_uid = (string)$data[1];
		
		$db = new DatabaseConnect();  
		$result = $db->query("UPDATE accounts 
								SET access_token='" . $access_token . "',
									expiration_date='" . $expiration_date . "'
								WHERE device_uid='" . $device_uid . "'");

		if($result === false){
				echo "Adding account failed: (" . $db->errno . ") " . $db->error;
		}	
	}
	
	return TRUE;
}

function isTokenFreshByAccessToken(Location $location, $accessToken)
{
	$db = new DatabaseConnect();  
	$result = $db->query("SELECT * FROM accounts WHERE access_token='" . $accessToken . "'");
	
	if ($result->num_rows > 0)
	{
			// output data of each row
			while($row = $result->fetch_assoc()) {
					//$access_token = $row["access_token"];
					$expiration_date = $row["expiration_date"];
					$deviceUid = $row["device_uid"];
					$access_token = $row["access_token"];
			}
	}
	else
	{
			echo '0 results';
	}

	if($expiration_date <= time()) {
		$accountCreator = new CreateUser();
		$accountCreator->setAccessToken($access_token);//$accountData->getAccessToken());
		$accountCreator->setDeviceUid($deviceUid);
		$accountCreator->setLocation($location);
		$data = $accountCreator->execute();

		$access_token = (string)$data[0]['access_token'];
		$expiration_date = $data[0]['expiration_date'];
		$device_uid = (string)$data[1];
		
		$db = new DatabaseConnect();  
		$result = $db->query("UPDATE accounts 
								SET access_token='" . $access_token . "',
									expiration_date='" . $expiration_date . "'
								WHERE device_uid='" . $device_uid . "'");

		if($result === false){
				echo "Adding account failed: (" . $db->errno . ") " . $db->error;
		}	
	}
	
	return TRUE;
}

function getKarma($accessToken)
{
	$accountCreator = new GetKarma();
	$accountCreator->setAccessToken($accessToken);
	$data = $accountCreator->execute();
	
	return $data["karma"];
}

function registerAccount(Location $location) {
	$accountCreator = new CreateUser();
	$accountCreator->setLocation($location);
	$data = $accountCreator->execute();
	
	$access_token = (string)$data[0]['access_token'];
	$refresh_token = (string)$data[0]['refresh_token'];
	$token_type = (string)$data[0]['token_type'];
	$expires_in = $data[0]['expires_in'];
	$expiration_date = $data[0]['expiration_date'];
	$distinct_id = (string)$data[0]['distinct_id'];
	$device_uid = (string)$data[1];

	$name = $location->cityName;
	$lat = $location->lat;
	$lng = $location->lng;
	
	$db = new DatabaseConnect();  
	$result = $db->query("INSERT INTO accounts (access_token, refresh_token, token_type,
					expires_in, expiration_date, distinct_id, device_uid, name, lat, lng)
					VALUES ('" . $access_token . "','" . $refresh_token . "','" . $token_type .
					"','" .  $expires_in . "','" . $expiration_date . "','" . $distinct_id .
					"','" . $device_uid . "','" . $name . "','" . $lat . "','" . $lng . "') ");

	$success = TRUE;
	if($result === false){
			$error = db_error();
			echo $error;
			echo "Adding account failed: (" . $result->errno . ") " . $result->error;
			$success = FALSE;
	}	
	
	return $access_token;
}

function getPosts($lastPostId, $accessToken, $url, $version = 'v2')
{	
	$accountCreator = new GetPosts();
	$accountCreator->setLastPostId($lastPostId);
	$accountCreator->setAccessToken($accessToken);
	$accountCreator->setUrl($url);
	$accountCreator->version = $version;

	$location = new Location();
	$location->setLat(52.520006);
	$location->setLng(13.404954);
	$location->setCityName('Berlin');
	$accountCreator->location = $location;
	$data = $accountCreator->execute();
	
	return $data;
}

function createAccount()
{
	$location = new Location();
	$location->setLat(52.520006);
	$location->setLng(13.404954);
	$location->setCityName('Berlin');

	$accessToken = registerAccount($location);

	return $accessToken;
}

function jodelToHtml($post, $view = 'time', $isDetailedView = FALSE)
{	//ToDO
	//Replace # with link
	//preg_replace('~(\#)([^\s!,. /()"\'?]+)~', '<a href="tag/$2">#$2</a>', $text);


	//Time to time difference
	$now = new DateTime();
	$d = new DateTime($post["created_at"]);
	$timediff = $now->diff($d);

	$timediff_inSeconds = (string)$timediff->format('%s');
	$timediff_inMinutes = (string)$timediff->format('%i');
	$timediff_inHours = (string)$timediff->format('%h');
	$timediff_inDays = (string)$timediff->format('%d');
	$timediff_inMonth = (string)$timediff->format('%m');

	if($timediff_inMonth!=0)
	{
			$timediff = $timediff_inMonth . "m";
	}
	else
	{
		if($timediff_inDays!=0)
		{
			$timediff = $timediff_inDays . "d";
		}
		else
		{
			if($timediff_inHours!=0)
			{
				$timediff = $timediff_inHours . "h";
			}
			else
			{
				if($timediff_inMinutes!=0)
				{
					$timediff = $timediff_inMinutes . "m";
				}
				else
				{
					$timediff = $timediff_inSeconds . "s";
				}
			}
		}
	}


	?>
	<article id ="postId-<?php echo $post["post_id"]; ?>" class="jodel" style="background-color: #<?php echo $post["color"];?>;">
		<content>
			<?php 
			if(isset($post["image_url"])) {
				echo '<img src="' . $post["image_url"] . '">';
			}
			else {
				echo str_replace('  ', ' &nbsp;', nl2br(htmlspecialchars($post["message"])));
			}
			?>
		</content>
		<aside>
			<a href="index.php?vote=up&postID=<?php echo $post["post_id"];?>">
				<i class="fa fa-angle-up fa-3x"></i>
			</a>	
				<br />
			<?php echo $post["vote_count"];?><br />
			<a href="index.php?vote=down&postID=<?php echo $post["post_id"];?>">
				<i class="fa fa-angle-down fa-3x"></i>
			</a>
		</aside>

		<footer>
			<table>
				<tr>
					<td class="time">
						<span data-tooltip="Time">
							<i class="fa fa-clock-o"></i>
							<?php echo $timediff;?>
						</span> 
					</td>
					<td class="comments">
						<?php if(!$isDetailedView) {?>
						<span data-tooltip="Comments">
							<a href="index.php?getPostDetails=true&view=<?php echo $view;?>&postID=<?php echo $post["post_id"];?>">
								<i class="fa fa-commenting-o"></i>
								<?php if(array_key_exists("child_count", $post)) {
											echo $post["child_count"];
										} else echo "0";
								?>
								</a>
						</span>
						<?php } ?>
					</td>
					<td class="distance">
						<?php
							if($isDetailedView)
							{
								if(isset($post["parent_creator"]) && $post["parent_creator"] == 1)
								{
									?>
									<span data-tooltip="Author">
										<i class="fa fa-user-o"></i> OJ |
									</span>
									<?php 
						  		}
						  		else
						  		{
						  			//Is not parent Jodel in detailed View
									if(!array_key_exists('child_count', $post) && array_key_exists('parent_creator', $post))
									{
							  			?>
							  			<span data-tooltip="Author">
											<i class="fa fa-user-o"></i> #<?php echo $post["user_handle"];?> |
										</span>
										<?php
									}
						  		}
						  	}
					  		?>

						<span data-tooltip="Distance">
							<i class="fa fa-map-marker"></i>
							<?php echo $post["distance"];?> km
						</span>
					</td>
				</tr>
			</table>
		</footer>
	</article>
<?php
}