<?php


class GetKarma extends AbstractRequest {
		
    function getApiEndPoint()
    {
        return '/v2/users/karma';
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

