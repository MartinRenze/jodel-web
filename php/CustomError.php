<?php

class CustomError
{
	public $view;
	public $actions;
	public $accountId;
	public $isVerified;
	public $isTokenFresh;
	public $page;
	public $referrer;
	public $msg;
	public $varsToDump;

	function __construct($msg = 'not Set', $varsToDump = 'not Set', $view = 'not Set', $actions = 'not Set', $accountId = 'not Set', $isVerified = 'not Set', $isTokenFresh = 'not Set', $page = 'not Set', $referrer = 'not Set')
    {
        $this->view = $view;
        $this->actions = $actions;
        $this->accountId = $accountId;
        $this->isVerified = $isVerified;
        $this->isTokenFresh = $isTokenFresh;
        $this->page = $page;
        $this->referrer = $referrer;
        $this->msg = $msg;
        $this->varsToDump = $varsToDump;
    }

	function writeErrorToLog()
	{
		error_log("=============================== ERROR ================================");
		error_log("msg: " . $this->msg);
		error_log("view: " . print_r($this->view, true));
		error_log("actions: " . $this->actionsToString());
		error_log("accountId: " . $this->accountId);
		error_log("isVerified: " . $this->isVerified);
		error_log("isTokenFresh: " . $this->isTokenFresh);
		error_log("page: " . $this->page);
		error_log("varsToDump: " . $this->varsToDumpToString());
		error_log("======================================================================");
	}

	function actionsToString()
	{

	}

	function varsToDumpToString()
	{
		
	}
}