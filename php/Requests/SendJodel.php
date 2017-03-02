<?php

class SendJodel extends AbstractRequest {
    public $location;
    public $ancestor = "";
    public $color = "";
    public $image = '';
    
    function getApiEndPoint()
    {
        return '/v3/posts/';
    }

    function getPayload()
    {
        if($this->image != '')
        {
            return array(
            "ancestor" => $this->ancestor,
            "color" => $this->color,
            "location" => $this->location->toArray(),
            "message" => $_POST['message'],
            'image' => base64_encode($this->image),
            );
        }
        else
        {
            return array(
            "ancestor" => $this->ancestor,
            "color" => $this->color,
            "location" => $this->location->toArray(),
            "message" => $_POST['message'],
            );
        }

    }
    function getMethod()
    {
        return 'POST';
    }
}
