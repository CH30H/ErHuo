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

//echo $uid;
$checksql = "SELECT * FROM User WHERE uid = '{$uid}';";
if($result = mysqli_query($con, $checksql))
{
	if(mysqli_num_rows($result))		// user exist in User table
	{
		file_put_contents("test.txt", "hello1\n");
		file_put_contents("test.txt", time(), FILE_APPEND);
		
		$status = 1;
	}
	else								// insert a new user
	{
		file_put_contents("test.txt", "hello2\n");
		file_put_contents("test.txt", time(), FILE_APPEND);
		// check the InactiveUser table
		// if in, status = 5 and send email again(alert the user to check the email)
		$checksql = "SELECT * FROM InactiveUser WHERE uid = '{$uid}';";
		if($result = mysqli_query($con, $checksql))
		{
			file_put_contents("test.txt", var_export($result, true), FILE_APPEND);
			if(mysqli_num_rows($result))		// user exist in InactiveUser table
			{
				$status = 5;
			}
		}
		// if not in, insert into InactiveUser and send a email
		else
		{
			file_put_contents("test.txt", "hello\n");
			file_put_contents("test.txt", time(), FILE_APPEND);
			$sql = "INSERT INTO InactiveUser (uid, passwd, username, school,
			gender,	grade, tel, wechatID, qq) VALUES ('{$uid}', '{$passwd}', 
			'{$username}', {$school}, {$gender}, {$grade}, '{$tel}', 
			'{$wechatID}', '{$qq}');";
			mysqli_query($con, $sql);
			// mail
			$mail_subject = "【二货】注册邮箱验证";
			$mail_message = $username."，您好：\n	感谢您使用二货二手物品交易平台！\n	请点击如下链接，以完成注册：\n"
							."\n"."	（如果不能点击该链接地址，请复制并粘贴到浏览器的地址输入框）\n	二货二手物品交易平台\n	".date("Y-m-d");
			$mail_from = "erhuo@example.com";
			$mail_header = "From:".$mail_from;
			$mail_to = "1400012782@pku.edu.cn";
			mail($mail_to, $mail_subject, $mail_message, $mail_header);
			//setcookie("uid", $uid, time() + 3600 * 24);
			$status = 0;
		}

	}
}
else		// fail to do sth with the tables in mysql
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