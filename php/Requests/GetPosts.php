<?php


class GetPosts extends AbstractRequest
{
		public $lastPostId;
		
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
        $apiEndPoint = '/v2/posts';
        //echo $GLOBALS['lastPostId'];
        if ($this->getLastPostId() != "") {
					$apiEndPoint = '/v2/posts/location/?after=' . $this->getLastPostId();
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
