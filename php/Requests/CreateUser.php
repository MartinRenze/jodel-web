<?php

class CreateUser extends AbstractRequest {
    /**
     * @var Location
     */
    private $location;
    private $deviceUid;
    /**
     * @return Location
     */
    private function getLocation()
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
    private function getDeviceUid()
    {
		return $this->deviceUid;
	}
	public function setDeviceUid($deviceUid)
    {
			$this->deviceUid = $deviceUid;
	}
    private function generateDeviceUid()
    {
        return $this->random_str(64, 'abcdef0123456789');
    }
    
    private function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }
    public function getApiEndPoint()
    {
        return '/v2/users';
    }
    public function getPayload()
    {
			if(!isset($this->deviceUid))
			{
				$this->setDeviceUid($this->generateDeviceUid());
			}

			return array(
					"location" => $this->getLocation()->toArray(),
					"client_id" => self::CLIENTID,
					"device_uid" => $this->getDeviceUid(),
			);
    }
    public function getMethod()
    {
        return 'POST';
    }
}
