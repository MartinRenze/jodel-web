<?php


class Downvote extends AbstractRequest {
		
    function getApiEndPoint()
    {
        return '/v2/posts/' . $_GET['postID'] . '/downvote';
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

