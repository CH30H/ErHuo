<?php

//$data = json_decode($_POST['json_f2b'], true);
$uid = $_POST['uid'];		// get message from client
$passwd = $_POST['passwd'];	// not encryptedn ye
//$passwd = MD5($data['passwd']);
$username = $_POST['nickname'];
/*$school = $_POST['school'];
$gender = $_POST['gender'];
$grade = $_POST['grade'];
$tel = $_POST['tel'];*/
$school = "0";
$gender = "0";
$grade = "0";
$tel = "0";
$wechatID = $_POST['wechatID'];
//$qq = $_POST['qq'];
$qq = "0";

// connect mysql
$mysql_server_name = 'localhost';
$mysql_user_name = 'group3';
$mysql_passwd = 'group3';
$mysql_database = 'group3';
$con = mysqli_connect($mysql_server_name, $mysql_user_name, $mysql_passwd, $mysql_database);	// connect mysql

if(!$con)		// fail to connect mysql
{
	$status = 3;
	$arr = array('status'=>$status);
	$json_b2f = json_encode($arr);
	echo $json_b2f;
	die();  
}

$checksql = "SELECT * FROM User WHERE uid = '{$uid}';";
if($result = mysqli_query($con, $checksql))
{
	if(mysqli_num_rows($result))		// user exist
	{
		$status = 1;
	}
	else								// user don't exist in User
	{
		$inactive_checksql = "SELECT * FROM InactiveUser WHERE uid = '{$uid}';";// try to find it in InactiveUser
		if($result = mysqli_query($con, $inactive_checksql))
		{
			if(mysqli_num_rows($result))		// user exist in InactiveUser
			{
				$status = 2;					// remind the user to active the account
				
				// !!!send the email again or not????
				
			}
			else								// user not exist, insert a user
			{
				$sql = "INSERT INTO InactiveUser (uid, passwd, username, school, gender,
						grade, tel, wechatID, qq) VALUES ('{$uid}', '{$passwd}', 
						'{$username}', {$school}, {$gender}, {$grade}, '{$tel}', 
						'{$wechatID}', '{$qq}');";
				mysqli_query($con, $sql);
				
				// !!!send email
				
				$status = 0;
			}
		}
		else							// sql query wrong
		{
			$status = 4;
			$arr = array('status'=>$status);
			$json_b2f = json_encode($arr);
			echo $json_b2f;
			die();
		}

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

mysqli_close($con);				// disconnect mysql

//echo $status;
$arr = array('status'=>$status);
$json_b2f = json_encode($arr);
echo $json_b2f;
?>