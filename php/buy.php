<?php

//include mail function
require_once('sendmailfunction.php');

$servername = 'localhost';  //?
$username = 'group3';
$passwd = 'group3';
$database = 'group3';

//setcookie("uid", '1055734930@qq.com', time() + 3600 * 24);

//check if cookie is wrong
if (!isset($_COOKIE["uid"]))		
{
	$arr = array('status'=>2);
	$json_b2f = json_encode($arr);
	echo $json_b2f;
	die();
}

else{
	$buyer_uid = $_COOKIE['uid'];
	//echo $buyer_uid;
}

//get gid from front end
$buying_gid = $_POST["gid"];


//set status, 0:succeed, 1: goods has been sold, 2:cookie wrong, 3: other failures
$status = 0;

// create connection
$conn = mysqli_connect($servername, $username, $passwd, $database);

//check contact
if(!$conn){
	die("Connection failed: " . mysqli_connect_error());
	//return error to front end
	$status = 3;
}

//echo "Connection successfully.";

//select seller's uid and sell_info from mysql with gid
$uid_query = "SELECT selled, uid 
			  FROM Goods
			  WHERE gid = '{$buying_gid}';";
$result = mysqli_query($conn, $uid_query);

if(!$result)
{
    die('Cannot read data: ' . mysqli_error($conn));
    //return error to front end
    $status = 3;
}

//define seller'uid
$seller_uid = NULL;

//define selled flag
$is_selled = NULL;

//get seller's information and selled flag
while($row = mysqli_fetch_array($result)){
	
	//check if the good has been sold
	$is_selled = $row["selled"];

	if($is_selled){
	 	//return error to front end
	 	$status = 1;
	 	break;
	}
	 
	 //get seller's uid
	 $seller_uid = $row["uid"];
}

//send email
if(!$is_selled){

	//define buyer's name and seller's name
	$buyer_name = NULL;
	$seller_name = NULL;

	//select buyer's username and buyer's username
	$buyer_name_query = "SELECT username, tel
			  FROM User
			  WHERE uid = '{$buyer_uid}';";

	$buyer_result = mysqli_query($conn, $buyer_name_query);

	if(!$buyer_result)
	{
	    die('Cannot read data: ' . mysqli_error($conn));
	    //return error to front end
	    $status = 3;
	}

	//get buyer's name
	while($row = mysqli_fetch_array($buyer_result)){
	 
		 $buyer_name = $row["username"];
		 $buyer_tel = $row["tel"];

	}
	//echo $buyer_name;
	//echo $buyer_tel;
	//select seller's username and buyer's username
	$seller_name_query = "SELECT username, tel
			  FROM User
			  WHERE uid = '{$seller_uid}';";

	$seller_result = mysqli_query($conn, $seller_name_query);

	if(!$seller_result)
	{
	    die('Cannot read data: ' . mysqli_error($conn));
	    //return error to front end
	    $status = 3;
	}
	
	//get seller's name
	while($row = mysqli_fetch_array($seller_result)){

	 	$seller_name = $row["username"];
	 	$seller_tel = $row["tel"];
	}	

	//send mail to seller
	$seller_subject = "【通知】您的商品已被购买";
	$seller_message = $seller_name . ", 您好！<br>&nbsp;&nbsp;您的商品已被" . $buyer_name . "购买，对方的联系方式为：" 
	. $buyer_tel . ",您可以通过电话与他（她）进行进一步的交流。请您在交易成功后7天内登陆二货确认交易。<br>祝您交易愉快。<br>二货";
	//$from = "erhuo@example.com";
	//$header = "From: ". $from;
	sendmail($seller_uid, $seller_subject, $seller_message);

	//send mail to buyer
	$buyer_subject = "【通知】您的订单已经成功生成";
	$buyer_message = $buyer_name . ",您好！<br>&nbsp;&nbsp;您的订单已经成功生成，您可以与卖家" . $seller_name . "联系，对方的联系方式为："
	. $seller_tel . "。<br>祝您交易愉快。<br>二货";
	sendmail($buyer_uid, $buyer_subject, $buyer_message);
}

//add good to selling
$now_date = date("Y-m-d");
$addsell = "INSERT INTO Selling (gid, uid, begindate)
           VALUES('{$buying_gid}', '{$seller_uid}', '{$now_date}');";

$result = mysqli_query($conn, $addsell);

if (!$result) {
	die('Cannot insert data: ' . mysqli_error($conn));
	$status = 3;
}

//return status to front end
$arr = array('status'=>$status);
echo json_encode($arr);

//close connection
mysqli_close($conn);

?>
