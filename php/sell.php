<?php
//cookie
if (!isset($_COOKIE["uid"]))		//cookie is wrong 
{
	$arr = array('status'=>2);
	$json_b2f = json_encode($arr);
	file_put_contents("test.txt", var_export($arr, true));
	file_put_contents("test.txt", time(), FILE_APPEND);
	echo $json_b2f;
	die();
}
else
{
	$uid = $_COOKIE["uid"];
}

file_put_contents("test.txt", time());

$uploaddir = '../photos/';
for($i = 1;$i <= 3;$i++)
{
	$filename = "photo".$i;
	
	if(!empty($_FILES[$filename]))
	{
		$fileinfo = $_FILES[$filename];
		file_put_contents("test.txt", var_export($fileinfo, true)."\n", FILE_APPEND);
		$$filename = time().$i.".".pathinfo($_FILES[$filename]['name'], PATHINFO_EXTENSION);//.".jpg";
		$uploadway = $uploaddir.$$filename;//$_FILES[$filename]['name'];
		//$$filename = $_FILES[$filename]['name'];
		
		if(!move_uploaded_file($_FILES[$filename]['tmp_name'], $uploadway)) // upload file
		{
			file_put_contents("test.txt", "failed\n", FILE_APPEND);
			$arr = array('status'=>1);
			$json_b2f = json_encode($arr);
			echo $json_b2f;
			die();
		}
	}
}

//$data = json_decode($_POST['json_f2b'], true);
$goodsname = $_POST['name'];
$price = $_POST['price'];
$newness = $_POST['newness'];
$descriptor1 = $_POST['mainCate'];
$descriptor2 = $_POST['nextCate'];
$description = $_POST['description'];

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

$sql = "INSERT INTO Goods (goodsname, price, uid, newness, goodsphoto1,
		goodsphoto2, goodsphoto3, descriptor1, descriptor2, description)
		VALUES ('{$goodsname}', '{$price}', '{$uid}', '{$newness}', '{$photo1}',
		'{$photo2}', '{$photo3}', '{$descriptor1}', '{$descriptor2}', '{$description}');";
mysqli_query($con, $sql);

mysqli_close($con);				// disconnect mysql

//echo $status;
$status = 0;
$arr = array('status'=>$status);
$json_b2f = json_encode($arr);
echo $json_b2f;
?>
