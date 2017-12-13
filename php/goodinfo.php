<?php

//get gid
$gid = $_POST["gid"];

//connect to database
$servername = 'localhost';  //?
$username = 'group3';
$passwd = 'group3';
$database = 'group3';

// create connection
$conn = mysqli_connect($servername, $username, $passwd, $database);

//check contact
if(!$conn){
	die("Connection failed: " . mysqli_connect_error());
	//return error to front end
	$status = 2;
}

$uid_query = "SELECT *
			  FROM Goods
			  WHERE gid = '{$gid}';";

$result = mysqli_query($conn, $uid_query);

if(!$result){
	die('Cannot read data: ' . mysqli_error($conn));
	$status = 2;
}


//get all results and return
while($row = mysqli_fetch_array($result)){
	//contain all information for good
	$good_info = array();
	$json_good_info = array();
	
	//check if good's been sold
	$good_info['selled'] = $row['selled'];
	if($good_info['selled']){
		$status = 1;
		$arr = array('status'=>$status);
        	echo json_encode($arr);
		break;
	}

	//return status
	$status = 0;
	$arr = array('status'=>$status);
   	echo json_encode($arr);

	//assignment
	$good_info['gid'] = $gid;
	$good_info['goodsname'] = $row['goodsname'];
	$good_info['uid'] = $row['uid'];
	$good_info['price'] = $row['price'];
	$good_info['newness'] = $row['newness'];
	$good_info['goodsphoto1'] = $row['goodsphoto1'];
	$good_info['goodsphoto2'] = $row['goodsphoto2'];
	$good_info['goodsphoto3'] = $row['goodsphoto3'];
	$good_info['type1'] = $row['type1'];
	$good_info['descriptor1'] = $row['descriptor1'];
	$good_info['descriptor2'] = $row['descriptor2'];
	$good_info['description'] = $row['description'];
	
	$json_good_info = json_encode($good_info);
	echo $json_good_info;
}
 mysqli_close($conn);

 ?>
