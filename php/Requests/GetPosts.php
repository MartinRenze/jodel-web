<?php


class GetPosts extends AbstractRequest
{
	public $lastPostId;

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
        //echo $GLOBALS['lastPostId'];
        if ($this->getLastPostId() != "") {
			$apiEndPoint = $this->getUrl() . '/location/?after=' . $this->getLastPostId();
		}
        return $apiEndPoint;
    }
    function getPayload()
    {
        return array(
        );
    }
    function getMethod()
    {
        return 'GET';
    }
}
