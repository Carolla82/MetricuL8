<?php
    error_reporting(E_ALL ^ E_NOTICE);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title> Home Page </title>
	<meta charset="utf8" />
	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body class="bgBody">

<?php
if (session_id() == "") {
    ini_set('session.use_only_cookies','1');
    session_start();
}

if(isset($_GET['Logout']) && $_GET['Logout']=='1') {
    unset($_SESSION['username']);
}
?>

<?php require 'master.php';?>

<div class="container">
	<div class="row">
		<div class="col-md-3" id="homeIcon">
			<span class="glyphicon glyphicon-user"></span>
		</div>
		<div class="col-md-9">
        	<h2>Welcome to the MetricuL8 Student Enrollment System!</h2>
        	<p>Use the links above to access your enrollment records. </p>
		</div>
	</div>
</div>

<?php require_once 'footer.php';?>
</body>
</html>