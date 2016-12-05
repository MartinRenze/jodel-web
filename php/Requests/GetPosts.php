<?php

class GetPosts extends AbstractRequest
{
	public $lastPostId;

    /**
     * @var Location
     */
    public $location;
    public $url;

        
    function setUrl ($url)
    {
            $this->url = $url;
    }
    
    function getUrl ()
    {
        return $this->url;
    }
		
    function setLastPostId ($lastPostId)
    {
			$this->lastPostId = $lastPostId;
	}
	
	function getlastPostId ()
	{
		return $this->lastPostId;
	}
    
    function getApiEndPoint()
    {
        $apiEndPoint = $this->getUrl();

        if ($this->getLastPostId() != "") {
			$apiEndPoint = $this->getUrl() . '?after=' . $this->getLastPostId();
		}
        return $apiEndPoint;
    }
    function getPayload()
    {
        if($this->version == 'v3')
        {
            $this->location = new Location();
            $this->location->setLat(52.520006);
            $this->location->setLng(13.404954);
            $this->location->setCityName('Berlin');


            return array(
                "location" => $this->location->toArray(),
                "stickies" => 'false',
            );
        }
        else
        {
            return array(
            );
        }
    }
    function getMethod()
    {
        return 'GET';
    }
}
