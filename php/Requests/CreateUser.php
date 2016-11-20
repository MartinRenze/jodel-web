<?php

class CreateUser extends AbstractRequest {
    /**
     * @var Location
     */
    public $location;
    public $deviceUid;
    /**
     * @return Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }
    /**
     * @param Location $location
     */
    public function setLocation(Location $location)
    {
        $this->location = $location;
    }
    public function getDeviceUid()
    {
		return $this->deviceUid;
	}
	public function setDeviceUid($deviceUid)
    {
			$this->deviceUid = $deviceUid;
	}
    public function generateDeviceUid()
    {
        return $this->random_str(64, 'abcdef0123456789');
    }
    function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }
    function getApiEndPoint()
    {
        return '/v2/users';
    }
    function getPayload()
    {
			if(!isset($this->deviceUid))
			{
				$this->setDeviceUid($this->generateDeviceUid());
			}
			echo $this->getDeviceUid();
            echo "<br>";
			return array(
					"location" => $this->getLocation()->toArray(),
					"client_id" => self::CLIENTID,
					"device_uid" => $this->getDeviceUid(),
			);
    }
    function getMethod()
    {
        return 'POST';
    }
}
