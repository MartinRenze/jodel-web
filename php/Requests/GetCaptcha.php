<?php
class GetCaptcha extends AbstractRequest
{		
    function getApiEndPoint()
    {
        return '/v3/user/verification/imageCaptcha/';
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

