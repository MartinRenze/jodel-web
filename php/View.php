<?php

class View
{
    public $country;
    public $city;
    public $hashtag;
	public $view;
    public $postId;
    public $isDetailedView;
    public $baseUrl;

	public $lastPostId = '';

    function __construct($baseUrl, $country, $city, $hashtag = '%23all', $view = 'time', $postId = '')
    {
        $this->baseUrl = $baseUrl;
        $this->country = $country;
        $this->city = $city;
        $this->hashtag = urldecode($hashtag);
        $this->view = $view;
        $this->postId = $postId;

        if($postId == '')
        {
            $this->isDetailedView = FALSE;
        }
        else
        {
            $this->isDetailedView = TRUE;
        }
    }
	/**
	 * Compute HTML Code
	 */
 	function jodelToHtml($post)
    {   //ToDO
        //Replace # with link
        //preg_replace('~(\#)([^\s!,. /()"\'?]+)~', '<a href="tag/$2">#$2</a>', $text);

        //Time to time difference
        $now = new DateTime();
        $d = new DateTime($post['created_at']);
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
        <article id ="postId-<?php echo $post['post_id']; ?>" class="jodel" style="background-color: #<?php echo $post['color'];?>;">
            <content>
                <?php 
                if(isset($post['image_url']))
                {
                    $regexRest = '/[^\w$ .!?-]+/u';

                    echo '<img src="' . $post['image_url'] . '" alt="' . htmlspecialchars(preg_replace($regexRest, '', $post['message'])) . '">';
                }
                else {
                    echo str_replace('  ', ' &nbsp;', nl2br(htmlspecialchars($post['message'])));
                }
                ?>
            </content>
            <aside>
                <?php
                    if($this->isDetailedView)
                    {?>
                        <a href="index.php?vote=up&getPostDetails=true&postId=<?php echo $post['post_id'];?>&postId_parent=<?php echo htmlspecialchars($_GET['postId']);?>" rel="nofollow">
              <?php }
                    else
                    {?>
                        <a href="index.php?vote=up&postId=<?php echo $post['post_id'];?>" rel="nofollow">
              <?php } ?>
                            <i class="fa fa-angle-up fa-3x"></i>
                        </a>    
                            <br />
                        <?php echo $post["vote_count"];?><br />
                <?php
                    if($this->isDetailedView)
                    {?>
                        <a href="index.php?vote=down&getPostDetails=true&postId=<?php echo $post['post_id'];?>&postId_parent=<?php echo htmlspecialchars($_GET['postId']);?>" rel="nofollow">
              <?php }
                    else
                    {?>
                        <a href="index.php?vote=down&postId=<?php echo $post['post_id'];?>" rel="nofollow">
              <?php } ?>
                            <i class="fa fa-angle-down fa-3x"></i>
                        </a>
            </aside>

            <footer>
                <span class="wrapper">
                    
                        <span class="time">
                            <span class="tip" data-tooltip="Time">
                                <i class="fa fa-clock-o"></i>
                                <?php echo $timediff;?>
                                <span class="tiptext"><?php echo $d->format('Y-m-d H:i:s');?></span>
                            </span> 
                        </span>
						<?php if(!$this->isDetailedView) {?>
                        <span class="comments">
                            <span data-tooltip="Comments">
                                <a href="<?php echo $this->changePostId($post['post_id'])->toUrl();?>">
                                    <i class="fa fa-commenting-o"></i>
                                    <?php if(array_key_exists("child_count", $post)) {
                                                echo $post["child_count"];
                                            } else echo "0";
                                    ?>
                                </a>
                            </span>
                            
                        </span>
						<?php
						}															  
						if (isUserAdmin())
						{
						?>
						<span class="voting">
							<a target="_blank" href="admin.php?postId=<?php echo $post['post_id'] ?>">
								<i class="fa fa-thumbs-o-up"></i> Vote
							</a>
						</span>
						<?php
						}
						?>
                        <span class="distance">
                            <?php
                                if($this->isDetailedView)
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

                            <span class="tip" data-tooltip="Distance">
                                <i class="fa fa-map-marker"></i>
                                <?php echo $post['distance'];?> km
                                <span class="tiptext"><?php echo $post['location']['name'];?></span>
                            </span>
                        </span>
                    
                </span>
            </footer>
        </article>
    <?php
    }


	/**
	 * Gets the title.
	 *
	 * @return     string  The title.
	 */
	function getTitle($post = '')
	{
		$title = 'JodelBlue - Web-App and Browser-Client';

		if($post != '' && $this->isDetailedView)
		{
			$title = 'JodelBlue: ' . substr(htmlspecialchars($post['message']), 0, 44);
		}

		return $title;
	}

	/**
	 * Gets the meta description.
	 *
	 * @return     string  The meta description.
	 */
	function getMetaDescription($post = '')
	{
		$description = 'JodelBlue is a Web-App and Browser-Client for the Jodel App. No registration required! Browse Jodels all over the world. Send your own Jodels or upvote others.';

		if($post != '' && $this->isDetailedView)
		{
			$description = 'On JodelBlue with ' . htmlspecialchars($post['vote_count']) . ' Upvotes: ' . substr(htmlspecialchars($post['message']), 0, 140);
		}

		return $description;
	}

	function getCaptcha($accessToken)
	{
		$accountCreator = new GetCaptcha();
		$accountCreator->setAccessToken($accessToken);
		$captcha = $accountCreator->execute();

		return array("image_url" => $captcha['image_url'], "key" => $captcha['key']);
	}

	function showCaptcha($accessToken, $deviceUid)
	{
		$accountCreator = new GetCaptcha();
		$accountCreator->setAccessToken($accessToken);
		$captcha = $accountCreator->execute();

		echo $captcha['image_url'];
		echo('<br><img width="100%" src="' . $captcha['image_url'] . '">');
		echo "<br>Key: " . $captcha['key'];
		echo "<br>";

		//Form
		
		echo '<form method="get">';
		echo	'<p>Enter Key (copy pasta from top): <input type="text" value="' . $captcha['key'] . '" name="key" /></p>';
		echo	'<p>Find the Coons (example: they are on picture 3, 4 and 5. You enter 2-3-4. Becouse we start counting at 0): <input type="text" name="solution" /></p>';
		echo 	'<input type="hidden" name="deviceUid" value="' . $deviceUid . '">';
		echo 	'<input type="hidden" name="pw" value="upVote">';
		echo	'<p><input type="submit" /></p>';
		echo '</form>';

		die();
		
	}

    function toUrl()
    {
        $url = $this->baseUrl . 'index.php?country=DE' .
                            '&city=' . urlencode($this->city) .
                            '&hashtag=' . urlencode($this->hashtag) . 
                            '&view=' . $this->view;
        if($this->postId != '')
        {
            $url .= '&postId=' . $this->postId . 
                    '&getPostDetails=TRUE';
        }

        return $url;
    }

    function changePostId($postId)
    {
        $tempView = clone $this;
        $tempView->postId = $postId;
        $tempView->isDetailedView = TRUE;
        return $tempView;
    }

    function back()
    {
        $tempView = clone $this;
        $tempView->postId = '';
        return $tempView;
    }

    function changeView($view)
    {
        $tempView = clone $this;
        $tempView->view = $view;
        return $tempView;
    }

	function getPosts($jodelAccount)
	{
        if($this->hashtag != '#all' && $this->hashtag != '' && $this->hashtag != NULL)
        {
            $accountCreator = new GetChannel();
            $accountCreator->view = $this->view;
            $accountCreator->setAccessToken($jodelAccount->accessToken);
            $accountCreator->channel = $this->hashtag;
            $accountCreator->lastPostId = $this->lastPostId;
            $data = $accountCreator->execute();
        }
        else
        {
            if($this->lastPostId == '' && $this->view == 'combo')
            {
                $url = "/v3/posts/location/combo";
            }
            else
            {
                if($this->view == 'discussed')
                {
                    $url = "/v2/posts/location/discussed/";
                }
                else
                {
                    if($this->view == 'popular')
                    {
                        $url = "/v2/posts/location/popular/";
                    }
                    else
                    {
                        $url = "/v2/posts/location/";
                    }
                }
            }

            $accountCreator = new GetPosts();
            $accountCreator->setLastPostId($this->lastPostId);
            $accountCreator->setAccessToken($jodelAccount->accessToken);
            $accountCreator->setUrl($url);
            $accountCreator->version = 'v3';

            $config = parse_ini_file('config/config.ini.php');
            $location = new Location();
            $location->setLat($config['default_lat']);
            $location->setLng($config['default_lng']);
            $location->setCityName($config['default_location']);
            $accountCreator->location = $location;
            $data = $accountCreator->execute();
        }
    	if(array_key_exists('recent', $data))
        {
            return $data['recent'];
        }
        else if(array_key_exists('posts', $data))
        {
            return $data['posts'];
        }
        else
        {
            error_log('Fehler View getPosts ');
            error_log(print_r($data, true));

            $notFound[0] = array(
                "post_id" => "0",
                "discovered_by" => 0,
                "message" => "Not found",
                "created_at" => "2017-02-11T16:44:50.385Z",
                "updated_at" => "2017-02-11T16:44:50.385Z",
                "pin_count" => 0,
                "color" => "FFBA00",
                "got_thanks" => FALSE,
                "post_own" => "friend",
                "discovered" => 0,
                "distance" => 9,
                "vote_count" => 0,
                "location" =>
                array("name" => "Berlin",
                  "loc_coordinates" =>
                  array(
                    "lat" => 0,
                    "lng" => 0
                  ),
                  "loc_accuracy" => 0,
                  "country" => "",
                  "city" => "",
                ),
                "tags" =>
                array(),
                "user_handle" => "0"
            );
            return $notFound;
        }
	}
}
