<?php
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

/*$t = time();
$checkstr = "login.php is being used at {$t}";
file_put_contents("../test.txt", $checkstr);

$data = json_decode($_POST['json_f2b'], true);

file_put_contents("test.txt", $data, FILE_APPEND);*/
$uid = $_POST['uid'];		// get message from client

$raw_passwd = $_POST['passwd'];	// not encryptedn yet
$encrypt_passwd = base64_decode($raw_passwd);
openssl_private_decrypt($encrypt_passwd, $passwd, $private_key);
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
		//$salt = $row["salt"];
		//$passwd = sha1($passwd.$salt);
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