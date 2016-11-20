<?php


class GetPostDetails extends AbstractRequest {
		
    function getApiEndPoint()
    {
        return '/v2/posts/' . $_GET['postID'];
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

