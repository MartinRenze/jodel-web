<?php

class JodelAccount
{
    public $accessToken;
    public $expirationDate;
    public $refreshToken;
    public $distinctId;
    public $deviceUid;

    //is the Account a Bot or Spider?
    public $isBot;
    
    // array of voted Jodels
    public $votes;

    //Location of the Account
    public $location;

    function __construct($deviceUid = NULL, $isBot = FALSE)
    {
        if($deviceUid == NULL)
        {
            $this->deviceUid = $this->createAccount();
        }
        else
        {
            $this->deviceUid = $deviceUid;
        }

        $this->isBot        = $isBot;
        $this->location     = $this->getLocation();

        if(!$this->isTokenFresh())
        {
            $this->refreshToken();
        }
        $this->accessToken  = $this->getAccessToken();
    }

    function isAccountVerified()
    {
        $accountCreator = new GetUserConfig();
        $accountCreator->setAccessToken($this->accessToken);
        $data = $accountCreator->execute();

        return $data['verified'];
    }

    function locationEquals($city)
    {
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . htmlspecialchars($city) . '&key=AIzaSyCwhnja-or07012HqrhPW7prHEDuSvFT4w';
        $result = Requests::post($url);
        if(json_decode($result->body, true)['status'] == 'ZERO_RESULTS' || json_decode($result->body, true)['status'] == 'INVALID_REQUEST')
        {
            error_log('Error locationEquals');
            return FALSE;
        }
        else
        {
            $name = json_decode($result->body, true)['results']['0']['address_components']['0']['long_name'];
            $lat = json_decode($result->body, true)['results']['0']['geometry']['location']['lat'];
            $lng = json_decode($result->body, true)['results']['0']['geometry']['location']['lng'];
        }

        $db = new DatabaseConnect();
        $result = $db->query("SELECT * FROM accounts WHERE device_uid='" . $this->deviceUid  . "'");
        
        $location = new Location();
        
        if ($result->num_rows > 0)
        {
            // output data of each row
            while($row = $result->fetch_assoc())
            {
                $location->setLat($row['lat']);
                $location->setLng($row['lng']);
                $location->setCityName($row['name']);
            }
        }
        else
        {
            echo "Error: 0 results";
            error_log("Error no Location found - getLocation");
        }

        if($location->getLat() == $lat && $location->getLng() == $lng && $location->getCityName() == $name)
        {
            return TRUE;
        }  
        else
        {
            return FALSE;
        }
    }

    function setLocation()
    {
        //Is Channel or City
        if(substr($_GET['city'], 0, 1) === '#')
        {
            return htmlspecialchars($_GET['city']) . " " . $this->location->cityName;
        }                
        else
        {
            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . htmlspecialchars($_GET['city']) . '&key=AIzaSyCwhnja-or07012HqrhPW7prHEDuSvFT4w';
            $result = Requests::post($url);
            if(json_decode($result->body, true)['status'] == 'ZERO_RESULTS' || json_decode($result->body, true)['status'] == 'INVALID_REQUEST')
            {
                return "0 results";
            }
            else
            {
                $name = json_decode($result->body, true)['results']['0']['address_components']['0']['long_name'];
                $lat = json_decode($result->body, true)['results']['0']['geometry']['location']['lat'];
                $lng = json_decode($result->body, true)['results']['0']['geometry']['location']['lng'];

                $location = new Location();
                $location->setLat($lat);
                $location->setLng($lng);
                $location->setCityName($name);
                $accountCreator = new UpdateLocation();
                $accountCreator->setLocation($location);
                $accountCreator->setAccessToken($this->accessToken);
                $data = $accountCreator->execute();

                //safe location to db
                $db = new DatabaseConnect();

                if($data == 'Success')
                {
                    $result = $db->query("UPDATE accounts 
                            SET name='" . $name . "',
                                lat='" . $lat . "',
                                lng='" . $lng . "'
                            WHERE access_token='" . $this->accessToken . "'");

                    if($result === false)
                    {
                            echo "Updating location failed: (" . $db->errno . ") " . $db->error;
                    }
                    else
                    {
                        user_log('User with JodelDeviceId:' . $this->deviceUid .  ' [' . $_SERVER['REMOTE_ADDR'] . '][' . $_SERVER ['HTTP_USER_AGENT'] . '] changed to Location: ' . $name);
                    }
                }

                return $name;
            }
        }
    }

    function getLocation()
    {
        $db = new DatabaseConnect();
        $result = $db->query("SELECT * FROM accounts WHERE device_uid='" . $this->deviceUid  . "'");
        
        $location = new Location();
        
        if ($result->num_rows > 0)
        {
            // output data of each row
            while($row = $result->fetch_assoc())
            {
                $location->setLat($row['lat']);
                $location->setLng($row['lng']);
                $location->setCityName($row['name']);
            }
        }
        else
        {
            echo "Error: 0 results";
            error_log("Error no Location found - getLocation");
        }

        return $location;
    }

    function verifyCaptcha()
    {
        if(isset($_GET['deviceUid']))
        {
            $deviceUid = $_GET['deviceUid'];
        }
        if(isset($_POST['deviceUid']))
        {
            $deviceUid = $_POST['deviceUid'];
        }
        $jodelAccountForVerify = new JodelAccount($deviceUid);

        $solution = $_GET['solution'];
        $solution = array_map('intval', explode('-', $solution));

        $accountCreator = new PostCaptcha();
        $accountCreator->setAccessToken($jodelAccountForVerify->accessToken);
        $accountCreator->captchaKey = $_GET['key'];
        $accountCreator->captchaSolution = $solution;
        $verified = $accountCreator->execute();

        if(isset($verified->status_code))
        {
            return $verified->status_code;
        }
        return $verified['verified'];
    }

    //ToDo Spider Check
    function votePostId($postId, $vote)
    {
        if(!$this->isAccountVerified())
        {
            $view = new View();
            $view->showCaptcha($this->accessToken, $this->deviceUid);
        }

        if(!$this->hasVoted($postId))
        {
            if($vote == "up")
            {
                $accountCreator = new Upvote();
            }
            else if($vote == "down")
            {
                $accountCreator = new Downvote();
            }

            $accountCreator->setAccessToken($this->accessToken);
            $accountCreator->postId = htmlspecialchars($postId);
            $data = $accountCreator->execute();

            if(array_key_exists('post', $data))
            {
                $this->addVoteWithPostIdAndType($postId, $vote);
                return TRUE;
            }
            else
            {
                error_log("Could not vote: " . print_r($data, true));
                return FALSE;
            } 
        }
        else
        {
            return FALSE;
        }
    }

    //ToDo Spider Check
    function sendJodel($location, $view)
    {
        if(!$this->isAccountVerified())
        {
            showCaptcha($this->accessToken);
        }

        $accountCreator = new SendJodel();

        if(isset($_POST['ancestor']))
        {
            $ancestor = $_POST['ancestor'];
            $accountCreator->ancestor = $ancestor;
        }
        if(isset($_POST['color']))
        {
            $color = $_POST['color'];
            switch ($color) {
                case '8ABDB0':
                    $color = '8ABDB0';
                    break;
                case '9EC41C':
                    $color = '9EC41C';
                    break;
                case '06A3CB':
                    $color = '06A3CB';
                    break;
                case 'FFBA00':
                    $color = 'FFBA00';
                    break;
                case 'DD5F5F':
                    $color = 'DD5F5F';
                    break;
                case 'FF9908':
                    $color = 'FF9908';
                    break;
                default:
                    $color = '8ABDB0';
                    break;
            }
            $accountCreator->color = $color;
        }

        $accountCreatorLocation = new UpdateLocation();
        $accountCreatorLocation->setLocation($location);
        $accountCreatorLocation->setAccessToken($this->accessToken);
        $data = $accountCreatorLocation->execute();
        
		if($data != 'Success')
		{
			error_log(print_r($data, true));
		}

        $accountCreator->location = $this->location;
        
        $accountCreator->setAccessToken($this->accessToken);
        $data = $accountCreator->execute();

        if(isset($_POST['ancestor']))
        {
            header('Location: ' . $view->toUrl());
            exit;
        }
        else
        {
            header('Location: ' . $view->baseUrl);
            exit;
        }
    }

    function isTokenFresh()
    {
        $db = new DatabaseConnect();  
        $result = $db->query("SELECT * FROM accounts WHERE device_uid='" . $this->deviceUid . "'");

        if ($result->num_rows > 0)
        {
            // output data of each row
            while($row = $result->fetch_assoc())
            {
                    $expiration_date = $row["expiration_date"];
            }
        }
        else
        {
            error_log('0 results');
        }

        if($expiration_date <= time())
        {
           return FALSE;
        }
        
        return TRUE;
    }

    function refreshToken()
    {
        $accountCreator = new CreateUser();
        $accountCreator->setAccessToken($this->accessToken);
        $accountCreator->setDeviceUid($this->deviceUid);
        $accountCreator->setLocation($this->location);
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
                error_log("Adding account failed: (" . $db->errno . ") " . $db->error);
        }   
    }



    function getAccessToken()
    {
        $db = new DatabaseConnect();
        $result = $db->query("SELECT * FROM accounts WHERE device_uid='" . $this->deviceUid  . "'");
        
        $accessToken;
        
        if ($result->num_rows > 0)
        {
            // output data of each row
            while($row = $result->fetch_assoc())
            {
                $accessToken = $row['access_token'];
            }
        }
        else
        {
            error_log('Error: 0 results');
        }

        return $accessToken;
    }


    function getKarma()
    {
        $accountCreator = new GetKarma();
        $accountCreator->setAccessToken($this->accessToken);
        $data = $accountCreator->execute();
        
        return $data["karma"];
    }

    function hasVoted($postId)
    {
        $db = new DatabaseConnect();

        $postId = $db->real_escape_string($postId);

        $result = $db->query("SELECT id FROM votes WHERE (postId = '" . $postId . "' AND device_uid = '" . $this->deviceUid . "')");
        
        if($result === false)
        {
            $error = db_error();
            echo $error;
            error_log("Adding Vote failed: (" . $result->errno . ") " . $result->error);
        }

        if($result->num_rows == 0)
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    function addVoteWithPostIdAndType($postId, $voteType)
    {
        $db = new DatabaseConnect();  

        $postId = $db->real_escape_string($postId);
        $voteType = $db->real_escape_string($voteType);
        
        if($this->hasVoted($postId))
        {
            return "Already voted";
        }

        $result = $db->query("INSERT INTO votes (device_uid, postId, type)
                        VALUES ('" . $this->deviceUid . "','" . $postId . "','" . $voteType . "')");
        
        if($result === false){
                $error = db_error();
                echo $error;
                echo "Adding Vote failed: (" . $result->errno . ") " . $result->error;
        }       
    }

    function registerAccount($location) {
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
        
        return $device_uid;
    }

    function createAccount()
    {
        $config = parse_ini_file('config/config.ini.php');
        $location = new Location();
        $location->setLat($config['default_lat']);
        $location->setLng($config['default_lng']);
        $location->setCityName($config['default_location']);

        $deviceUid = $this->registerAccount($location);

        return $deviceUid;
    }
}