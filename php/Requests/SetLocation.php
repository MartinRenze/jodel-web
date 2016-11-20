<?php


class SetLocation extends AbstractRequest {
		 /**
     * @var Location
     */
    public $location;
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
		
    function getApiEndPoint()
    {
        return '/v2/users/location';
    }
    function getPayload()
    {
        return array(
					"location" => $this->getLocation()->toArray(),
        );
    }
    function getMethod()
    {
        return 'PUT';
    }
}

