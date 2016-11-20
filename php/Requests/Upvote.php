<?php


class Upvote extends AbstractRequest {
		
    function getApiEndPoint()
    {
        return '/v2/posts/' . $_GET['postID'] . '/upvote';
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

