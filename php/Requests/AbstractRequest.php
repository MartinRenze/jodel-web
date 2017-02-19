<?php

abstract class AbstractRequest
{	
    const CLIENTID = '81e8a76e-1e02-4d17-9ba0-8a7020261b26';
    const APIURL = 'https://api.go-tellm.com/api';
    const SECRET = 'SDydTnTdqqaiAMfneLkqXYxamvNuUYOmkqpdiZTu';
    const USERAGENT = 'Jodel/4.34.2 Dalvik/2.1.0 (Linux; U; Android 5.1.1; )';
    const CLIENT_TYPE = 'android_4.34.2';
    
    private $accessToken = null;
    private $payLoad;
    public $expects = '';
    public $version = 'v2';
    public $hasPayload = FALSE;

    public function execute()
    {
		$result = new \stdClass();
		        
		$this->payLoad = $this->getPayload();
		$device_uid = '';
		if(isset($this->payLoad['device_uid'])) {
			$device_uid = $this->payLoad['device_uid'];
		}
				
				
        $this->payLoad = json_encode($this->payLoad);
        $header = $this->getSignHeaders();
        $url = $this->getFullUrl();

        if ($this->getAccessToken()) {
            $header['Authorization'] = "Bearer " . $this->getAccessToken();
        }
        //Comment out to debug the Request:

        /*
        printf("URL: ");
        var_dump($url);
        echo "<br />";
        printf("Header: ");
        var_dump($header);
        echo "<br />";
        printf("Payload: ");
        var_dump($this->payLoad);
        echo "<br />";
        */
        /*
        $options = array(
            'timeout' => 100,
            'connect_timeout' => 100,
            'proxy' => '186.103.169.165:8080',
        );*/

        switch ($this->getMethod()) {
            case 'POST':
                $result = Requests::post($url, $header, $this->payLoad);
                break;
            case 'GET':
                if($this->hasPayload)
                {
                    $result = Requests::get($url, $header, $this->payLoad);
                }
                else
                {
                    $result = Requests::get($url, $header);
                }
                break;
            case 'PUT':
                $result = Requests::put($url, $header, $this->payLoad);
                break;
        }
        switch ($result->status_code) {
            case 200:
                $result = json_decode($result->body, true);
                break;
            case 204:
                $result = "Success";
                break;
            case 400:
                //throw new \Exception('Unauthorized');
                error_log('Error 400 - Fehlerhafte Anfrage'); // - JodelDeviceId:' . $deviceUid);
                break;
            case 401:
				//throw new \Exception('Unauthorized');
                error_log(print_r($header, true));
                error_log('Error 401 - Unauthorized'); // - JodelDeviceId:' . $deviceUid);
                error_log(print_r($result, true));
                break;
            case 404:
                //echo "Es wurde bereits gevoted";
                error_log('Error 404 - Es wurde bereits gevoted'); // - JodelDeviceId:' . $deviceUid);
			case 477:
                //echo "Es wurde bereits gevoted";
                //throw  new \Exception('Signing failed!');
                error_log('Error 477 - Signing failed'); // - JodelDeviceId:' . $deviceUid);
                break;
            case 429:
                error_log('Error 429 - Too Many Requests'); // - JodelDeviceId:' . $deviceUid);
            	exit("Error 429: Too Many Requests");
            	break;
            case 403:
                error_log('Error 403 - Access denied'); // - JodelDeviceId:' . $deviceUid);
                exit("Error 403: Access denied");
                break;
            default:
                error_log('Error '.$result->status_code.' - Unauthorized'); // - JodelDeviceId:' . $deviceUid);
                //throw  new \Exception('Unknown Error: '.$result->status_code);
        }

        if($device_uid != '')
        {
			$result[0] = $result;
			$result[1] = $device_uid;
	}

        /*
        var_dump($result);
        */

        return $result;
    }
    abstract function getPayload();
    /**
     * Gets Sign headers
     * @return array headers
     */
    private function getSignHeaders()
    {
			if($this->getAccessToken() == null) {
				$payload_accessToken = "";
			}
			else {
				$payload_accessToken = $this->getAccessToken();
			}
			
			
        $headers = array(
            "Connection" => "keep-alive",
            "Accept-Encoding" => "gzip",
            "Content-Type" => "application/json; charset=UTF-8",
            "User-Agent" => self::USERAGENT
        );
        $timestamp = new DateTime();
        $timestamp = $timestamp->format(DateTime::ATOM);
        $timestamp = substr($timestamp, 0, -6);
        $timestamp .= "Z";
        $urlParts = parse_url($this->getFullUrl());
        $url2 = "";
        $req = [$this->getMethod(),
            $urlParts['host'],
            "443",
            $urlParts['path'],
            $payload_accessToken,
            $timestamp,
            $url2,
            $this->payLoad];
        $reqString = implode("%", $req);
        $secret = self::SECRET;
        $signature = hash_hmac('sha1', $reqString, $secret);
        $signature = strtoupper($signature);
        $headers['X-Authorization'] = 'HMAC ' . $signature;
        $headers['X-Client-Type'] = self::CLIENT_TYPE;
        $headers['X-Timestamp'] = $timestamp;
        $headers['X-Api-Version'] = '0.2';
        return $headers;
    }
    private function getFullUrl()
    {
        return self::APIURL . $this->getApiEndPoint();
    }
    abstract function getApiEndPoint();
    abstract function getMethod();
    /**
     * @return string
     */
    private function getAccessToken()
    {
        return $this->accessToken;
    }
    /**
     * @param string $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }
}
