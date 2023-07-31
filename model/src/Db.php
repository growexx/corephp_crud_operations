<?php

require_once("../../model/phpConfigs/include.php");
require_once(MYSQL_CONNECTION);
class Db
{
	public $_db;

	public function __construct()
	{
		try 
		{ 
			$this->_db=mysqli_connect(DBMYSQL_HOST,DBMYSQL_USERNAME,DBMYSQL_PASSWORD);
			$dbSelect = mysqli_select_db($this->_db,DBMYSQL_NAME);

			if (empty($this->_db) || empty($dbSelect)) 
			{
				throw new Exception(mysqli_error());
			}

		} catch (Exception $e) 
		{ 
			die($e->getMessage());
		} 
	}

	public function __destruct() {
		mysqli_close($this->_db);
	}

	public function _executeQuery($query)
	{
		mysqli_query($this->_db,"SET character_set_results=utf8");
		mb_language('uni');
		mb_internal_encoding('UTF-8');
		@mysqli_query("SET NAMES 'utf8'");
		@mysqli_query("SET CHARACTER_SET utf8");
		return mysqli_query($this->_db,$query);;
	}

	public function getAll($res)
	{
		$dt=array();
		$i=0;
		while($row=@mysqli_fetch_assoc($res))
		{
			$dt[$i]=$row;
			$i++;
		}
		return($dt);
	}

	public function getMysqlInsertId()
	{
		return mysqli_insert_id();
	}
}

?>