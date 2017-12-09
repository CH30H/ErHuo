<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>LoginAndRegister</title>
<link rel = "stylesheet" type = "text/css" href = "../css/LoginAndRegister.css">
<script src="../js/jquery.min.js"></script>
</head>

<body>
<div class="login-page">
  <div class="form">
	<p>
	  	<?php
		echo $_GET['msg'];
		?>
	</p>
  </div>
</div>
</body>
</html>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>LoginAndRegister</title>
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/LoginAndRegister.css">
</head>
<body>
    <div class="container" id="wrap">
      <h2 class="text-center">ErHuo</h2>
      <br>
      <div id="loginForm">
	  <p>
	  	<?php
		echo $_GET['msg'];
		?>
	</p>
      </div>
    </div>
    <script src="../js/jquery.min.js"></script> 
    <script src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
