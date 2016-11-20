<?php

class AccountData
{
    /**
     * @var string
     */
    public $accessToken;
    /**
     * @var string
     */
    public $expirationDate;
    /**
     * @var string
     */
    public $refreshToken;
    /**
     * @var string
     */
    public $distinctId;
    /**
     * @var string
     */
    public $deviceUid;
    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }
    /**
     * @param string $accessToken
     */
    public function setAccessToken(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }
    /**
     * @return string
     */
    public function getExpirationDate(): string
    {
        return $this->expirationDate;
    }
    /**
     * @param string $expirationDate
     */
    public function setExpirationDate(string $expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }
    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
    /**
     * @param string $refreshToken
     */
    public function setRefreshToken(string $refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }
    /**
     * @return string
     */
    public function getDistinctId(): string
    {
        return $this->distinctId;
    }
    /**
     * @param string $distinctId
     */
    public function setDistinctId(string $distinctId)
    {
        $this->distinctId = $distinctId;
    }
    /**
     * @return string
     */
    public function getDeviceUid(): string
    {
        return $this->deviceUid;
    }
    /**
     * @param string $deviceUid
     */
    public function setDeviceUid(string $deviceUid)
    {
        $this->deviceUid = $deviceUid;
    }
}
