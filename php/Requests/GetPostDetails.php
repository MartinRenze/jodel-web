<?php


class GetPostDetails extends AbstractRequest {
		
    function getApiEndPoint()
    {
        return '/v2/posts/' . $_GET['postId'];
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

