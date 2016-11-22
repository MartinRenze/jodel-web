<?php

class SendJodel extends AbstractRequest {
     /**
     * @var Location
     */
    public $location;
    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }
    /**
     * @param Location $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }
    
    public $ancestor;
    /**
     * @return Location
     */
    public function getAncestor()
    {
        return $this->ancestor;
    }
    /**
     * @param Location $location
     */
    public function setAncestor($ancestor)
    {
        if(isset($ancestor) && $ancestor != "")
        {
					$this->ancestor = $ancestor;
				}
    }
    
    function getApiEndPoint()
    {
        return '/v3/posts/';
    }
    function getPayload()
    {
        return array(
						"ancestor" => $this->getAncestor(),
						"color" => "9EC41C",
            "location" => $this->getLocation()->toArray(),
            "message" => $_POST['message'],
        );
    }
    function getMethod()
    {
        return 'POST';
    }
}
