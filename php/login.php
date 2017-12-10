<?php

/*$t = time();
$checkstr = "login.php is being used at {$t}";
file_put_contents("../test.txt", $checkstr);

$data = json_decode($_POST['json_f2b'], true);

file_put_contents("test.txt", $data, FILE_APPEND);*/
$uid = $_POST['uid'];		// get message from client
$passwd = $_POST['passwd'];	// not encryptedn ye
//$passwd = MD5($data['passwd']);
file_put_contents("test.txt", $uid, FILE_APPEND);
file_put_contents("test.txt", $passwd, FILE_APPEND);

// connect mysql
$mysql_server_name = 'localhost';
$mysql_user_name = 'group3';
$mysql_passwd = 'group3';
$mysql_database = 'group3';
$con = mysqli_connect($mysql_server_name, $mysql_user_name, $mysql_passwd, $mysql_database);

if(!$con)		// fail to connect mysql
{
	$status = 3;
	$arr = array('status'=>$status);
	$json_b2f = json_encode($arr);
	echo $json_b2f;
	die();  
}

// check the uid and password in User
$status = 1;	// user not exist
$active_sql = "SELECT uid, passwd FROM User WHERE uid = '{$uid}';";
if($result = mysqli_query($con, $active_sql))
{
	if(mysqli_num_rows($result))		// user exist
	{
		$row = mysqli_fetch_assoc($result);
		if($passwd == $row["passwd"])
		{
			$status = 0;			// login successfully, set cookie
			setcookie("uid", $uid, time() + 3600 * 24);	
		}
		else						// password wrong
		{
			$status = 2;
		}
	}
	else								// user don't exist in User
	{
		$inactive_sql = "SELECT uid, passwd FROM InactiveUser WHERE uid = '{$uid}';";// try to find it in InactiveUser
		if($result = mysqli_query($con, $inactive_sql))
		{
			if(mysqli_num_rows($result))		// user exist in InactiveUser
			{
				$status = 5;					// remind the user to active the account
				// send the email again or not????
			}
		}
		else
		{
			$status = 4;
			$arr = array('status'=>$status);
			$json_b2f = json_encode($arr);
			echo $json_b2f;
			die();
		}
	}
	file_put_contents("test.txt", "the user has been checked", FILE_APPEND);
}
else
{
	$status = 4;
	$arr = array('status'=>$status);
	$json_b2f = json_encode($arr);
	echo $json_b2f;
	die();
}

mysqli_close($con);				// disconnect mysql

//echo $status;
$arr = array('status'=>$status);
$json_b2f = json_encode($arr);
file_put_contents("test.txt", $arr, FILE_APPEND);
echo $json_b2f;
?>