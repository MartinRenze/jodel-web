<?php

class SendJodel extends AbstractRequest {
    public $location;
    public $ancestor = "";
    public $color = "";
    
    function getApiEndPoint()
    {
        return '/v3/posts/';
    }

    function getPayload()
    {
        return array(
			"ancestor" => $this->ancestor,
			"color" => $this->color,
            "location" => $this->location->toArray(),
            "message" => $_POST['message'],
        );
    }
    function getMethod()
    {
        return 'POST';
    }
}
