<?php
class Downvote extends AbstractRequest
{
    public $postId;
		
    function getApiEndPoint()
    {
        return '/v2/posts/' . $this->postId . '/downvote';
    }
    function getPayload()
    {
        return array(
        );
    }
    function getMethod()
    {
        return 'PUT';
    }
}

