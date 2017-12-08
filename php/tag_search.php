<?php
$t1 = $_POST['type'];
$t2 = $_POST['descriptor1'];
//$t3 = $_POST['descriptor2'];
$servername = "localhost";
$username = "group3";
$password = "group3";
$dbname = "group3";
$conn = mysqli_connect($servername,$username,$password,$dbname);
if(!$conn){
    $status = 3;
    $arr = array('status'=>$status);
    $json_b2f = json_encode($arr);
    echo $json_b2f;
    die('Could not connect: ' .  mysqli_connect_error()); 
}

if($t1 == "0" && $t2 == "0"){
    $sql = "select * from Goods ";
}
else if($t1 == "0"){
    $sql = "select * from Goods where  descriptor2 = $t2  ";
}
else if($t2 == "0"){
    $sql = "select * from Goods where  descriptor1 = $t1  ";
}
else{
$sql = "select * from Goods where  descriptor1 = $t1 and descriptor2 = $t2  ";
}
/*
$result = mysqli_query($conn,$sql);
$num0 = mysqli_num_rows($result);
if($num0 > 100){
    $num0 = 100;
}

$i =0;
$goodsname0 = array();
$price0 = array();
$newness0 = array();
$goodsphoto10 = array();
$goodsphoto20 = array();
$goodsphoto30 = array();
$type0 = array();
$descriptor10 = array();
$descriptor20 = array();
$description0 = array();


for($i =0 ; $i<$num0;$i++) {
    $row = mysqli_fetch_row($result);
    array_push($goodsname0,$row[1]);
    array_push($price0,$row[3]);
    array_push($newness0,$row[4]);
    array_push($goodsphoto10,$row[5]);
    array_push($goodsphoto20,$row[6]);
    array_push($goodsphoto30,$row[7]);
    array_push($type0,$row[8]);
    array_push($descriptor10,$row[9]);
    array_push($descriptor20,$row[10]);
    array_push($description0,$row[11]);
}

$num = json_encode($num0);
$goodsname = json_encode($goodsname0);
$price = json_encode($price0);
$newness = json_encode($newness0);
$goodsphoto1 = json_encode($goodsphoto10);
$goodsphoto2 = json_encode($goodsphoto20);
$goodsphoto3 = json_encode($goodsphoto30);
$type = json_encode($type0);
$descriptor1 = json_encode($descriptor10);
$descriptor2 = json_encode($descriptor20);
$description = json_encode($description0);
echo $num;
echo $goodsname;
echo $price;
echo $newness;
echo $goodsphoto1;
echo $goodsphoto2;
echo $goodsphoto3;
echo $type;
echo $descriptor1;
echo $descriptor2;
echo $description;
*/


$result = mysqli_query($conn,$sql);
$num = mysqli_num_rows($result);
if($num > 100){
    $num = 100;
}

$i = 0;
$myarr = array();
$json_arr = array();
array_push($json_arr, $num);
for($i =0 ; $i<$num;$i++) {
    $row = mysqli_fetch_row($result);
    $myarr = array();
    $myarr['gid'] = $row[0];
    $myarr['goodsname'] = $row[1];
    $myarr['uid'] = $row[2];
    $myarr['price'] = $row[3];
    $myarr['newness'] = $row[4];
    $myarr['goodsphoto1'] = $row[5];
    $myarr['goodsphoto2'] = $row[6];
    $myarr['goodsphoto3'] = $row[7];
    $myarr['type'] = $row[8];
    $myarr['descriptor1'] = $row[9];
    $myarr['descriptor2'] = $row[10];
    $myarr['description'] = $row[11];
    $myarr['selled'] = $row[12];
    array_push($json_arr, $myarr);
}

$json_b2f = json_encode($json_arr);
echo $json_b2f;

/*
if(mysqli_query($conn,$sql)){
    echo '<script type="text/javascript">alert("SUCCEED");document.location="../CheSK_Test_For_PHP.html";</script>';
}
else{
    echo '<script type="text/javascript">alert("WRONG!");document.location="../CheSK_Test_For_PHP.html";</script>';
}

*/