<?php

class DatabaseConnect extends mysqli
{
 // The database connection
	public function __construct()
	{
		$config = parse_ini_file('config/config.ini.php'); 
		
		parent::__construct($config['host'], $config['username'], $config['password'], $config['dbname']);
	}

}
$db = new DatabaseConnect();
if ($db->connect_errno) {
  echo 'Sorry, die Verbindung zu unserem 
        Server ist hops gegangen. Wegen '.$db -> connect_error;
}

$query = "CREATE TABLE IF NOT EXISTS `accounts` (
			 `id` int(11) unsigned NOT NULL auto_increment,
			 `access_token` varchar(55) NOT NULL default '',
			 `refresh_token` varchar(55) NOT NULL default '',
			 `token_type` varchar(55) NOT NULL default 'bearer',
			 `expires_in` INT NOT NULL,
			 `expiration_date` INT NOT NULL,
			 `device_uid` varchar(255) NOT NULL default '',
			 `client_id` varchar(255) NOT NULL default '81e8a76e-1e02-4d17-9ba0-8a7020261b26',
			 `distinct_id` varchar(255) NOT NULL default '',
			 `city` varchar(100) default '',
			 `country` varchar(10) default 'DE',
			 `loc_accuracy` varchar(50) default '0.0',
			 `lat` varchar(255) default '',
			 `lng` varchar(255) default '',
			 `name` varchar(100) default '',
			 `X-Client-Type` varchar(50) NOT NULL default 'android_4.24.2',
			 `User-Agent` varchar(150) NOT NULL default 'Jodel/4.4.9 Dalvik/2.1.0 (Linux; U; Android 5.1.1; )',
			 `X-Api-Version` varchar(10) NOT NULL default '0.2',
			 PRIMARY KEY  (`id`)
			 ) DEFAULT CHARSET=utf8";
$query2 = "CREATE TABLE IF NOT EXISTS `votes` (
			 `id` int(11) unsigned NOT NULL auto_increment,
			 `device_uid` varchar(255) NOT NULL,
			 `postId` varchar(255) NOT NULL,
			 `type` varchar(255) NOT NULL,
			 PRIMARY KEY  (`id`)
			 ) DEFAULT CHARSET=utf8";		 
		 
  if(!$db->query($query) || !$db->query($query2))
  {
    throw new Exception($db->error($mysqli));
  }

