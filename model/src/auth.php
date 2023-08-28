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

	function checkEmailExist($Email)
	{
		$sql = "SELECT * FROM user WHERE Mail='$Email' ";
		$res = $this->_executeQuery($sql);
		$res1 = $this->getAll($res);
		if ($res1)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public function register()
	{
		$registerData = $_REQUEST['registerData'];
		$registerData = json_decode($registerData, true);

		$Email = $registerData['user_email'];
		$Password = $registerData['user_password'];
		$Hobbies = $registerData['hobbies'];


		$emailExist = $this->checkEmailExist($Email);

		if($emailExist==0) {
			$imgFile =  $_FILES['userImg']['name'];
			$pat = $_SERVER['DOCUMENT_ROOT'] . "/corephp_crud_operations/FileManager";
			$fileUrl = "http://" . $_SERVER['HTTP_HOST'] . "/corephp_crud_operations/FileManager/$imgFile";
			$fileName = $pat . '/' . $imgFile;
			move_uploaded_file($_FILES["userImg"]["tmp_name"], $fileName);
			$path_to_store_file = "corephp_crud_operations/FileManager/" . $imgFile;

			$sql = "INSERT INTO user(Mail, Password, Hobbies, UserImg)
			VALUES('$Email','$Password','$Hobbies','$path_to_store_file')";
			$res = $this->_executeQuery($sql);

			if ($res)
			{
				$arr['status'] = 'Success';
				$arr['response_text'] = 'Successfully Registered';
			}
			else
			{
				$arr['status'] = 'Fail';
				$arr['response_text'] = 'Not Registered, Kindly Mail The Issue..';
			}
		} 
		else
		{
			$arr['status'] = 'Fail';
			$arr['response_text'] = 'Email-ID Already Registered';
		}
		return $arr;
	}

	public function login()
	{
		$Email = $_POST['email'];
		$Password = $_POST['password'];
		$EmailExist = $this->checkEmailExist($Email);

		if($EmailExist==1) {

			$sql = "SELECT * FROM user WHERE Mail='$Email' and Password='$Password'";
			$res = $this->_executeQuery($sql);
			$res1 = $this->getAll($res);

			if ($res1)
			{
				$arr['status'] = "Success";
				$arr['response_text'] = "Successfully Logged-IN";
				$arr['login'] = $res1;
			}
			else {
				$arr['status'] = "Fail";
				$arr['response_text'] = "Email not registered with us.";
			}
		} 
		else{
			$arr['status'] = 'Fail';
			$arr['response_text'] = 'Email not Registered';
		}

		return $arr;
	}

	public function getUser()
	{
		$sql = "SELECT * from user order by UserID desc";
		$res = $this->_executeQuery($sql);
		$res1 = $this->getAll($res);

		if ($res1)
		{
			$arr['status'] = 'Success';
			$arr['userList'] = $res1;
		}
		else
		{
			$arr['status'] = 'Fail';
			$arr['response_text'] = 'No User Found';
		}

		return $arr;
	}

	public function deleteUser()
	{
		$UserID = $_POST['UserID'];

		$sql = "DELETE from user WHERE UserID='$UserID'";
		$res = $this->_executeQuery($sql);

		if ($res)
		{
			$arr['status'] = 'Success';
			$arr['response_text'] = 'Successfully Removed';
		}  
		else
		{
			$arr['status'] = 'Fail';
			$arr['response_text'] = 'Unable To Remove..!!';
		}

		return $arr;
	}
}
?>