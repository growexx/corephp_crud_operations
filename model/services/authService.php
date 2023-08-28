<?php
session_start();
set_time_limit(120);
error_reporting(E_ALL);
require_once("../../model/phpConfigs/include.php");

// make dynamic path

require_once(MYSQL_DB);
require_once(MYSQL_QUERY_AUTH);
date_default_timezone_set('Asia/Calcutta');

class authService
{
	public $mysqlQueries;
	public $command;
	public function __construct()
	{
		$this->command=strtoupper($_REQUEST['cmd']);

		$this->mysqlQueries=new MysqlQueriesAuth();
		switch($this->command)
		{
			case 'REGISTER'  			        : $this->register();   								break;
			case 'LOGIN'  			            : $this->login();   								break;
			case 'GET_USER'  			        : $this->getUser();   								break;
			case 'DELETE_USER'  			    : $this->deleteUser();   							break;
			default 							: $this->defaultError(); 							break;
		}
	}

	function deleteUser()
	{
		$res=$this->mysqlQueries->deleteUser();
		echo json_encode($res);     
	}  

	function getUser()
	{
		$res=$this->mysqlQueries->getUser();
		echo json_encode($res);     
	}

	function register()
	{
		$res=$this->mysqlQueries->register();
		echo json_encode($res);     
	}  
	
	function login()
	{
		$res=$this->mysqlQueries->login();
		echo json_encode($res);     
	}

	function defaultError()
	{
		$str=$_SERVER['QUERY_STRING'];
		if($str!='')
		{
			if($this->command=='')
			{
				$error['status']="FAILURE";
				$error['status_value']=0;
				$error['response_text']= "Post parameters are empty..";
			}
			if($this->command!='')
			{
				$error['status']="FAIL";
				$error['status_value']=0;
				$error['response_text']= "Invalid cmd parameter";
			}
		}else{
			$error['status']="FAIL";
			$error['status_value']=0;
			$error['response_text']= "Required parameters missing";
		}
		echo json_encode($error);
	}
}

$obj=new authService();
?>
