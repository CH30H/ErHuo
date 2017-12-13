<?php
$token = $_GET['token'];
$uid = $_GET['uid'];
//file_put_contents("test_active.txt", $uid, FILE_APPEND);
//file_put_contents("test_active.txt", $token, FILE_APPEND);

// connect mysql
$mysql_server_name = 'localhost';
$mysql_user_name = 'group3';
$mysql_passwd = 'group3';
$mysql_database = 'group3';
$con = mysqli_connect($mysql_server_name, $mysql_user_name, $mysql_passwd, $mysql_database);	// connect mysql

if(!$con)		// fail to connect mysql
{
	/*$status = 3;
	$arr = array('status'=>$status);
	$json_b2f = json_encode($arr);
	echo $json_b2f;*/
	echo "对不起，连接失败！请重新尝试连接。";
	die();  
}


$checksql = "SELECT * FROM InactiveUser WHERE uid = '{$uid}';";
if($result = mysqli_query($con, $checksql))
{
	if(mysqli_num_rows($result))		// user exist in inactiveUser
	{
		$row = mysqli_fetch_assoc($result);
		$check_token = $row["token"];
		$regtime = $row["regTime"];
		date_default_timezone_set("PRC");
		$nowtime = time();
		if($check_token == $token)		// right
		{
			if($nowtime - $regtime <= 24*60*60)	// in a day
			{
				// move the infomation in InactiveUser to User
				$passwd = $row["passwd"];
				$username = $row["username"];
				$school = $row["school"];
				$grade = $row["grade"];
				$gender = $row["gender"];
				$tel = $row["tel"];
				$wechatID = $row["wechatID"];
				$qq = $row["qq"];
				$salt = $row["salt"];
				$insert_sql = "INSERT INTO User (uid, passwd, username, school, gender,
						grade, tel, wechatID, qq, salt) VALUES ('{$uid}', '{$passwd}', 
						'{$username}', {$school}, {$gender}, {$grade}, '{$tel}', 
						'{$wechatID}', '{$qq}', '{$salt}');";
				mysqli_query($con, $insert_sql);
				//echo $insert_sql;
				
				// delete the line in InactiveUser
				$delete_sql = "DELETE FROM InactiveUser WHERE uid = '{$uid}';";
				mysqli_query($con, $delete_sql);
				//echo $delete_sql;
				
				setcookie("uid", $uid, $nowtime + 3600 * 24);
				echo "激活成功!";
				header("refresh:2;url = ../Shop.html");
				exit;
			}
			else						// out of date
			{
				echo "对不起，链接已失效，请重新注册帐号。";
				// !!!maybe we can jump to the LoginAndRegister.html after 5s
				header("refresh:2;url = ../LoginAndRegister.html");
				exit;
			}
		}
		else
		{
			echo "对不起，该链接无效。";
			header("refresh:2;url = ../LoginAndRegister.html");
			exit;
		}
	}
	else								// don't exist in InactiveUser
	{
		echo "对不起，该链接无效。";
		header("refresh:2;url = ../LoginAndRegister.html");
		exit;
	}
}
else
{
	/*$status = 4;
	$arr = array('status'=>$status);
	$json_b2f = json_encode($arr);
	echo $json_b2f;*/
	echo "对不起，连接失败！请重新尝试连接。";
	die();
}
?>