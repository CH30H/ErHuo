<?php
include 'sendmailfunction.php';

$private_key = '-----BEGIN PRIVATE KEY-----
MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAOc8No/Q57NSIeeL
Pb1WtUunj4SNdNLDoj9GoppJuy5W+g9TOeaVQO9MVYLpw+LvmRQag5vC8il2LFeg
B4oxyAS8xN7g/DJ6WNWroNCZFLCP3j98a4T8gQTpOuJIOViQrp1MkkuTPNmb9zEi
VGbGHnYQCB5/B5iZZU1nwyNy9FP3AgMBAAECgYAbsZlzRfjKADcxTPra90yFQA8v
N+Xa7DU9UM9enR/+38nZFgWkORHq1wCSyj58qmdyAe7rM8De+Fk9MVxVz7p9pHUB
69phIju2KOP/7ZgqFnN2czHPL1n+6zgv8RJFh0TTTlsZjjb/hVnP8Sz61CBMOarP
aNYaNqhJvxh9MZjmYQJBAPoVtYx+F9kEN5gipSUGTBY4qtwn5Wvqn7OXIKybQ7vw
4PBedQpTEEjdwKutyj66VRRwyezZBAPW/wOmUJnpEpECQQDstF4bTr8flzSs6Vm/
hpdmlNHCRhb3IAZCKR3FbHxu51WlzX477BxaQ5Gy/gVq0NNCyjVN3j8bQ3kKLwOB
7LIHAkAIRd+Tnjg7vZ/5MGw2JVcvBQDh94/nWgOedUlnbFt5RCaszPMiPE01m+Bb
zYv7Nz7JRlHnu+YeGmalQEM6VDOBAkBSM0TnCM64gsMJNTQ0neHf/thlNf/trBJg
UXUERWtk/DMzFAy9dH5YHlTvquVotcJX1G70brTNm/3hunfmW7NrAkEA5vR6erTn
4XddqFZWXhC8MYH+gdZnHFzziIRgK6DMuomfrSLp8cMItqixeD76QOrwUmoK1kQw
ycF4kt+7kU/E7w==
-----END PRIVATE KEY-----';

//$data = json_decode($_POST['json_f2b'], true);
$uid = $_POST['uid'];		// get message from client

$raw_passwd = $_POST['passwd'];
$encrypt_passwd = base64_decode($raw_passwd);
openssl_private_decrypt($encrypt_passwd, $passwd, $private_key);
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

$raw_wechatID = $_POST['wechatID'];	// not encryptedn yet
$encrypt_wechatID = base64_decode($raw_wechatID);
openssl_private_decrypt($encrypt_wechatID, $wechatID, $private_key);
//$wechatID = $_POST['wechatID'];
//$qq = $_POST['qq'];
$qq = "0";

// mail message
$mail_subject = "【二货】注册邮箱验证";
$mail_to = $uid;
$mail_message1 = $username."，您好：<br>"."    感谢您使用二货二手物品交易平台！请点击如下链接，以完成注册：<br>		";
$mail_message2 = "<br>		（如果不能点击该链接地址，请复制并粘贴到浏览器的地址输入框）
								<br><br>								二货二手物品交易平台<br>								".date("Y-m-d");
			
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
				$row = mysqli_fetch_assoc($result);
				$regtime = $row["regTime"];
				$regcount = $row["regCount"];
				$token = $row["token"];
				date_default_timezone_set("PRC");
				$nowtime = time();
				if($nowtime - $regtime <= 24*60*60)// in a day
				{
					if($regcount)
					{
						$regcount = $regcount - 1;
						$change_sql = "UPDATE InactiveUser SET regCount = '{$regcount}' WHERE uid = '{$uid}';";
						mysqli_query($con, $change_sql);
						
						$file_url = $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
						$dir_url = dirname($file_url);
						$url = "http://".$dir_url."/active.php?uid=".$uid."&token=".$token;
						
						// !!!send again						
						$mail_message = $mail_message1."<a href='".$url."'>{$url}</a>".$mail_message2;
						sendmail($mail_to, $mail_subject, $mail_message);
					}
				}
				else	// out of a day, renew
				{
					$regcount = 2;
					$regtime = $nowtime;					
					// new token
					$token = md5($uid.$passwd.$regtime);
					
					$salt = base64_encode(mcrypt_create_iv(32, MCRYPT_DEV_RANDOM));
					$passwd = sha1($passwd.$salt);
					
					// restore in mysql
					$delete_sql = "DELETE FROM InactiveUser WHERE uid = '{$uid}';";
					mysqli_query($con, $delete_sql);

					$insert_sql = "INSERT INTO InactiveUser (uid, passwd, username, school, gender,
							grade, tel, wechatID, qq, salt, regTime, regCount, token) VALUES ('{$uid}', '{$passwd}', 
							'{$username}', {$school}, {$gender}, {$grade}, '{$tel}', 
							'{$wechatID}', '{$qq}', '{$salt}', '{$regtime}', '{$regcount}', '{$token}');";
					mysqli_query($con, $insert_sql);
					
					// !!!send new mail again
					$file_url = $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
					$dir_url = dirname($file_url);
					$url = "http://".$dir_url."/active.php?uid=".$uid."&token=".$token;
					
					// !!!send again						
					$mail_message = $mail_message1."<a href='".$url."'>{$url}</a>".$mail_message2;
					sendmail($mail_to, $mail_subject, $mail_message);
				}
			}
			else								// user not exist, insert a user
			{
				//echo $passwd."\n";
				$salt = base64_encode(mcrypt_create_iv(32, MCRYPT_DEV_RANDOM));
				$passwd = sha1($passwd.$salt);
				
				date_default_timezone_set("PRC");
				$regtime = time();
				$regcount = 2;
				$token = md5($uid.$passwd.$regtime);// use to active
				//echo $salt."\n";
				//echo $passwd."\n";
				
				$file_url = $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
				$dir_url = dirname($file_url);
				$url = "http://".$dir_url."/active.php?uid=".$uid."&token=".$token;
				//echo $url;
				
				$sql = "INSERT INTO InactiveUser (uid, passwd, username, school, gender,
						grade, tel, wechatID, qq, salt, regTime, regCount, token) VALUES ('{$uid}', '{$passwd}', 
						'{$username}', {$school}, {$gender}, {$grade}, '{$tel}', 
						'{$wechatID}', '{$qq}', '{$salt}', '{$regtime}', '{$regcount}', '{$token}');";
				mysqli_query($con, $sql);
				
				// !!!send email				
				$mail_message = $mail_message1."<a href='".$url."'>{$url}</a>".$mail_message2;
				sendmail($mail_to, $mail_subject, $mail_message);

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
