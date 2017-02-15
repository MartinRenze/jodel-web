<?php

class GetChannel extends AbstractRequest
{
    /**
     * @var Location
     */
    public $location;
    public $channel;
    public $hasPayload = FALSE;
    public $lastPostId = '';
    public $view = 'combo';
		
    function getApiEndPoint()
    {
        if($this->lastPostId == '')
        {
            $apiEndPoint = '/v3/posts/hashtag/' . $this->view . '?hashtag=' . $this->channel;
        }
        else
        {
            if($this->view == 'combo')
            {
                $apiEndPoint = '/v3/posts/hashtag?hashtag=' . $this->channel . '&after=' . $this->lastPostId;
            }
            else
            {
                $apiEndPoint = '/v3/posts/hashtag/' . $this->view . '?hashtag=' . $this->channel . '&after=' . $this->lastPostId;
            }
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
