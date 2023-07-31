<?php
ob_start();
error_reporting(0);
set_time_limit(200);
require_once("../../model/phpConfigs/include.php");
require_once (MYSQL_CONNECTION);
require_once (MYSQL_DB);
date_default_timezone_set("UTC");

class MysqlQueriesAuth extends Db
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
}
?>