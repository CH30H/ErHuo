<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function sendmail($to, $subject, $body){
//	use PHPMailer\PHPMailer\PHPMailer;
//	use PHPMailer\PHPMailer\Exception;

	//这里需要保证src目录的相对路径
	require 'src/Exception.php';
	require 'src/PHPMailer.php';
	require 'src/SMTP.php';
        //echo 'get';
	$mail = new PHPMailer(true);
	$mail->Charset = 'UTF-8';
	try{
		//比较重要的配置
		$mail->SMTPDebug = 0;  //用来debug
		$mail->isSMTP();
		$mail->Host = 'smtp.163.com';
		$mail->SMTPAuth = true; //身份验证
		$mail->Username = 'feifeiilei@163.com';
		$mail->Password = 'helloworldgroup3';
		$mail->SMTPSecure = 'tls';
		$mail->Port = 25;

		//邮件显示内容
		$mail->setFrom('feifeiilei@163.com', 'ErHuo');
		$mail->addAddress($to);  //目标地址
		$mail->addReplyTo('feifeiilei@163.com', 'ErHuo'); //收到邮件后reply，reply all的地址

		//邮件
		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Subject = '=?utf-8?B?'.base64_encode($mail->Subject).'?='; //设置title的编码格式
		$mail->Body = $body;
		$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		$mail->send();
		//echo 'Message has been sent';

	} catch (Exception $e){
		echo 'Message could not be sent.';
		echo 'Mailer Error: '.$mail->ErrorInfo;
	}

}
