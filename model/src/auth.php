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

	function checkEmailExist($email)
	{
		$sql = "SELECT * FROM user WHERE Mail='$email' ";
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

		$email = $registerData['user_email'];
		$password = $registerData['user_password'];
		$hobbies = $registerData['hobbies'];


		$emailExist = $this->checkEmailExist($email);

		if($emailExist==0) {
			$imgFile =  $_FILES['userImg']['name'];
			$pat = $_SERVER['DOCUMENT_ROOT'] . "/filemanager";
			$fileUrl = "http://" . $_SERVER['HTTP_HOST'] . "/filemanager/$imgFile";
			$fileName = $pat . '/' . $imgFile;
			move_uploaded_file($_FILES["userImg"]["tmp_name"], $fileName);
			$path_to_store_file = "filemanager/" . $imgFile;

			$sql = "INSERT INTO user(Mail, Password, Hobbies, UserImg)
			VALUES('$email','$password','$hobbies','$path_to_store_file')";
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
		$email = $_POST['email'];
		$password = $_POST['password'];
		$emailExist = $this->checkEmailExist($email);

		if($emailExist==1) {

			$sql = "SELECT * FROM user WHERE Mail='$email' and Password='$password'";
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
		$userID = $_POST['UserID'];

		$sql = "DELETE from user WHERE UserID='$userID'";
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

	public function viewUser()
	{
		$userID = $_GET['UserID'];

		$sql = "SELECT * from user WHERE UserID='$userID'";
		$res = $this->_executeQuery($sql);
		$res1 = $this->getAll($res);

		if ($res1)
		{
			$arr['status'] = 'Success';
			$arr['userDetails'] = $res1;
		}
		else
		{
			$arr['status'] = 'Fail';
			$arr['response_text'] = 'No User Found';
		}

		return $arr;
	}

	public function editUser()
	{
		$userID = $_POST['UserID'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$hobbies = $_POST['hobbies'];

		$sql = "UPDATE user SET Mail='$email', Password='$password', Hobbies='$hobbies' WHERE UserID='$userID'";
		$res = $this->_executeQuery($sql);

		if ($res)
		{
			$arr['status'] = 'Success';
			$arr['response_text'] = 'Successfully Updated';
		}
		else
		{
			$arr['status'] = 'Fail';
			$arr['response_text'] = 'UnSuccessfully Updated';
		}

		return $arr;
	}
}
?>