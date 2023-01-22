<?php
    error_reporting(E_ALL ^ E_NOTICE);
    
    if (session_id() == "") {
        ini_set('session.use_only_cookies','1');
        session_start();
    }    
    if(isset($_SESSION['username']))
        echo "Welcome: " . $_SESSION['username'];
    require_once 'MetricuL8Store.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>
<div class="jumbotron" id="jt">
	<div class="containter text-center">
		<h1>MetricuL8</h1>
		<h2>Student Enrollment System</h2>
	</div>
</div>
<nav class="navbar navbar-inverse">
	<div class="containter-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#epNavBar">
    			<span class="icon-bar"></span>
    			<span class="icon-bar"></span>
    			<span class="icon-bar"></span>			
			</button>
		</div>
		<div class="collapse navbar-collapse" id="epNavBar">
			<ul class="nav navbar-nav">
				<li class="active"><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php
				    if (session_id() == "")
				    {
    				    ini_set('session.use_only_cookies','1');
    				    session_start();
				    }
				    
				    if(isset($_SESSION['username']))
				    {
				        echo "<li><a href=\"enrollments.php\"><span class=\"glyphicon glyphicon-tags\"></span>&nbspEnrollments</a></li>";
				        
				        if ($_SESSION['username']=="admin@metricul8.com")
				        {
				            echo "<li><a href=\"classes.php\"><span class=\"glyphicon glyphicon-calendar\"></span>Classes</a></li>";
				        }
				        
				        echo "<li><a href=\"index.php?Logout=1\"><span class=\"glyphicon glyphicon-off\"></span>Logout</a></li>";
				    }
				    else 
				    {
				        echo "<li><a href=\"login.php\"><span class=\"glyphicon glyphicon-user\"></span> Login</a></li>";
				        echo "<li><a href=\"registration.php\"><span class=\"glyphicon glyphicon-pencil\"></span> Registration</a></li>";
				    }
				?>			
			</ul>
		</div>
	</div>
</nav>
</body>
</html>