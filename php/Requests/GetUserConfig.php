<?php
class GetUserConfig extends AbstractRequest
{
		
    function getApiEndPoint()
    {
        return '/v3/user/config/';
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

