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
                <button onclick="vote('<?php echo $post['post_id'];?>', 'up', this)">
                    <i class="fa fa-angle-up fa-3x"></i>
                </button>    
                <br><span><?php echo $post["vote_count"];?></span><br>

                <button onclick="vote('<?php echo $post['post_id'];?>', 'down', this)">
                    <i class="fa fa-angle-down fa-3x"></i>
                </button>
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
                                    <?php if(array_key_exists("child_count", $post))
                                    {
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
                                    if(isset($post['user_handle']) && $post['user_handle'] == 'OJ')
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
                                        if(!array_key_exists('child_count', $post))
                                        {
                                            ?>
                                            <span data-tooltip="Author">
                                                <i class="fa fa-user-o"></i> #<?php echo $post['user_handle'];?> |
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
		$title = 'JodelBlue - Top Jodel from ' . htmlspecialchars($this->city) . ' Web-App and Browser-Client';

		if($post != '' && array_key_exists('message', $post) && $post['message'] != '' && $this->isDetailedView)
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
		$description = 'JodelBlue is a Web-App and Browser-Client for the Jodel App. No registration required! Browse Jodels in ' . htmlspecialchars($this->city) . ' or all over the world.';

		if($post != '' && array_key_exists('message', $post) && $post['message'] != '' && $this->isDetailedView)
		{
			$description = 'On JodelBlue in ' . htmlspecialchars($this->city) .  ' with ' . htmlspecialchars($post['vote_count']) . ' Upvotes: ' . substr(htmlspecialchars($post['message']), 0, 130);
		}

		return $description;
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
    	if(array_key_exists('recent', $data) && array_key_exists(0, $data['recent']))
        {
            return $data['recent'];
        }
        else if(array_key_exists('posts', $data)&& array_key_exists(0, $data['posts']))
        {
            return $data['posts'];
        }
        else
        {
            if($this->lastPostId == '')
            {
                error_log('Could not find Posts in: ' . $this->city . ' Error: ' . print_r($data, true));
                //error_log(print_r($data, true));

                $notFound[0] = array(
                    "post_id" => "0",
                    "discovered_by" => 0,
                    "message" => "No more Posts found",
                    "created_at" => "2017-02-11T16:44:50.385Z",
                    "updated_at" => "2017-02-11T16:44:50.385Z",
                    "pin_count" => 0,
                    "color" => "5682a3",
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
            else
            {
                return FALSE;
            }
        }
	}
}
