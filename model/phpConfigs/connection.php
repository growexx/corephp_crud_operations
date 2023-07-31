<?php
$host=$_SERVER['HTTP_HOST'];

header("Access-Control-Allow-Origin: *");

switch($host)
{
	case 'www.xyz.com': 
	case 'xyz.com':   
		define('DBMYSQL_NAME', '');
	 	define('DBMYSQL_USERNAME', '');
		define('DBMYSQL_PASSWORD', '');
	 	define('DBMYSQL_HOST', '');
		define("FROM_EMAIL","");
	break;

	default :
		define("DBMYSQL_HOST", "localhost");
		define("DBMYSQL_USERNAME", "root");
		define("DBMYSQL_PASSWORD", "Root@123");
		define("DBMYSQL_NAME", "PLI");
	break;
}
?>

