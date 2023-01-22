<?php
    error_reporting(E_ALL ^ E_NOTICE);
    require_once 'MetricuL8Store.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title> Login Page </title>
	<meta charset="utf8" />
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

if( !isset($_SESSION['username']) && $_SERVER['REQUEST_METHOD']=='POST' ) {
    $dbStore = new MetricuL8Store();
    
    if( $dbStore->checkCred($_POST['usernameTry'], $_POST['passKey']) ) {
        
        $_SESSION['username'] = $_POST['usernameTry'];
        
        if(isset($_SESSION['upError'])){
            unset($_SESSION['upError']);
        }
    } else {
        $_SESSION['upError'] = true;
    }
}
?>

<?php require 'master.php';?>
<?php
if (session_id() == "") {
    ini_set('session.use_only_cookies','1');
    session_start();
}

if (isset($_SESSION['username'])) {
    
    echo makeWelcomeForm($_SESSION['username']);    
} else {
        echo makeLoginForm();
}


function makeWelcomeForm($uName){
    $welcome = '<div class="container"><p>Welcome '. $uName .'</p></div>';
    return $welcome;
}

function makeLoginForm() {
    
    if(isset($_POST['usernameTry'])) {
        
        $usernameTry = $_POST['usernameTry'];
        $passKey = $_POST['passKey'];
    } else{
        
        $usernameTry='';
        $passKey='';
    }    
    
    $html =  '<div class="container">';
    $html .= '<h1>Log in to MetricuL8</h1>';

    if (isset($_SESSION["upError"])) {
        
        $html .= '<span class="error">Error unable to log in.</span>';
        
        unset($_SESSION['upError']);
    }
    
    $html .='<form class="form-horizontal" role="form" method="post" action="'
            . htmlspecialchars($_SERVER["PHP_SELF"]) .'">';
    
    $html .= '<div class="form-group">'
    	    .'<label class="control-label col-sm-2" for="f-usernameTry">User Email</label>'
    		.'<div class="col-sm-10">'
            .'<input type="email" name="usernameTry" id="f-usernameTry" placeholder="email@provider.com" value="'
            .$usernameTry
            .'" /></div></div>';
    
    $html .= '<div class="form-group">'
    		.'<label class="control-label col-sm-2" for="f-password">Password</label>'
    		.'<div class="col-sm-10">'
            .'<input type="password" name="passKey" id="f-password" value="'.$passKey.'" />'
    		.'</div></div>';
    
    $html .= '<div class="form=group">'
    		.'<div class="col-sm-5 col-sm-offset-4">'
    		.'<button type="submit" title="Login" class="ButtonFont">'
    		.'<span class="glyphicon glyphicon-ok"></span>'
    		.'</button></div></div>'
            .'</form></div>';
    
    return $html;
}

?>

<?php require_once 'footer.php';?>
</body>
</html>