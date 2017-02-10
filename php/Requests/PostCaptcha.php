<?php
class PostCaptcha extends AbstractRequest
{
	public $captchaKey;
    public $captchaSolution;

    function getApiEndPoint()
    {
        return '/v3/user/verification/imageCaptcha/';
    }
    function getPayload()
    {
        return array(
            'key' => $this->captchaKey,
            'answer' => $this->captchaSolution,
        );
    }
    function getMethod()
    {
        return 'POST';
    }
}

