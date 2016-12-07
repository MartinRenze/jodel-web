<?php
class Upvote extends AbstractRequest {
		
    public $postId;

    function getApiEndPoint()
    {
        return '/v2/posts/' . $this->postId . '/upvote';
    }
    function getPayload()
    {
        return array(
					"reason_code" => -1,
        );
    }
    function getMethod()
    {
        return 'PUT';
    }
}

