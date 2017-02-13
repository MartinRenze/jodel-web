<?php

class GetChannel extends AbstractRequest
{
    /**
     * @var Location
     */
    public $location;
    public $channel;
    public $hasPayload = FALSE;
		
    function getApiEndPoint()
    {
        $apiEndPoint = '/v3/posts/hashtag/combo?hashtag=' . $this->channel;
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
