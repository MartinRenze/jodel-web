<?php

class Location{

    public $cityName;

    public $lat;

    public $lng;

    public function getCityName()
    {
        return $this->cityName;
    }

    public function setCityName(string $cityName)
    {
        $this->cityName = $cityName;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function setLat(string $lat)
    {
        $this->lat = $lat;
    }

    public function getLng()
    {
        return $this->lng;
    }

    public function setLng(string $lng)
    {
        $this->lng = $lng;
    }
    public function toArray(){
        return array(
            "city" => $this->getCityName(),
            "country" => 'DE',
            "loc_accuracy" => '0.0',
            "loc_coordinates" => array(
                "lat" => $this->getLat(),
                "lng" => $this->getLng(),
            ),
            "name" => $this->getCityName()
        );
    }
}
