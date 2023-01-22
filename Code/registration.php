<?php
    error_reporting(E_ALL ^ E_NOTICE);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title> Registration Page </title>
	<meta charset="utf8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">	
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body class="bgBody">

<?php require 'master.php';?>

<?php 

if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $anyErr      = false;
    $errorsExist = "";
    
    $emailError         = "";
    $passKeyError       = "";
    $lastNameError      = "";
    $firstNameError     = "";
    $phoneError         = "";
    $dobError           = "";
    
    if (empty($_POST["emailAddr"])) {
        $anyErr = true;
        $emailError = "Email required";
    } else {
        $emailAddr = $_POST["emailAddr"];
    }
    
    if (empty($_POST["passKey"])) {
        $anyErr = true;
        $passKeyError = "Password required";
    } else {
        $passKey = $_POST["passKey"];
    }
    
    if (empty($_POST["firstName"])) {
        $anyErr = true;
        $firstNameError = "First name required";
    } else {
        $firstName = $_POST["firstName"];
    }
    
    if (empty($_POST["lastName"])) {
        $anyErr = true;
        $lastNameError = "Last name required";
    } else {
        $lastName = $_POST["lastName"];
    }
    

    if (empty($_POST["birthDate"])) {
        $anyErr = true;
        $dobError = "Birth Date required";
    } else {
        $birthDate = $_POST["birthDate"];
    }    
    
    $phoneArea = $_POST["phoneArea"];
    $phoneNum = $_POST["phoneNum"];
    
    if ( empty($_POST["phoneArea"]) || empty($_POST["phoneNum"]) ) {
        $anyErr = true;
        $phoneError = "Phone# required";
    }
    
    if ($anyErr)
    {
        $errorsExist = "Fix the errors below";
    } else {
        
    // save to the database
    $dbStore = new MetricuL8Store();
    
    // get the max ID from the database
    $sql = 'select max(id)+1 AS maxid from metricul8v2.student';
    if($qResult = $dbStore->executeSelectQuery($sql)){
        foreach ($qResult as $row){
            $newId = $row['maxid'];
        }
    } else {
        $newId = 1;
    }
    
    // build the insert statement
    $sql = "insert into metricul8v2.student(id, email, password, firstName, "
        . "lastName, phoneArea, phoneNumber, birthDate) values("
        . strval($newId)
        . ",'" . $_POST["emailAddr"] . "'"
        . ",'" . $_POST["passKey"] . "'"
        . ",'" . $_POST["firstName"] . "'"
        . ",'" . $_POST["lastName"] . "'"
        . "," . strval($_POST["phoneArea"])
        . "," . strval($_POST["phoneNum"])
        . ",'" . strval($_POST["birthDate"]) . "'"
        . ")";
    
        if ($dbStore->executeQuery($sql)) {
            $errorsExist = "Student Added!";
            
            $emailAddr     = "";
            $passKey       = "";
            $lastName      = "";
            $firstName     = "";
            $phoneArea     = 0;
            $phoneNum      = 0;
            $birthDate     = "";
            
            $emailError         = "";
            $passKeyError       = "";
            $lastNameError      = "";
            $firstNameError     = "";
            $phoneError         = "";
            $dobError           = "";
        } else{
            $errorsExist = "Can't save to the database. Contact your system administrator.";
        }
    }
}

?>

<div class="container">
	<h1>New Student Registration</h1>
	<p class="error"><?php echo $errorsExist;?></p>
    <form class="form-horizontal" role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    	<div class="form-group">
    		<label class="control-label col-sm-2" for="f-email">Email Address</label>
    		<div class="col-sm-10">
    			<input type="email" name="emailAddr" id="f-email" placeholder="email@provider.com" value="<?php echo $emailAddr;?>" />
    			<span class="error"><?php echo $emailError; ?></span>
    		</div>
    	</div>
    	
    	<div class="form-group">
    		<label class="control-label col-sm-2" for="f-password">Password</label>
    		<div class="col-sm-10">
    			<input type="password" name="passKey" id="f-password" placeholder="8 or more characters" value="<?php echo $passKey;?>" />
    			<span class="error"><?php echo $passKeyError; ?></span>
    		</div>
    	</div>

    	<div class="form-group">
    		<label class="control-label col-sm-2" for="f-firstName">First Name</label>
    		<div class="col-sm-10">
    			<input type="text" name="firstName" id="f-firstName" value="<?php echo $firstName;?>" />
    			<span class="error"><?php echo $firstNameError; ?></span>
    		</div>
    	</div>
    	
    	<div class="form-group">
    		<label class="control-label col-sm-2" for="f-lastName">Last Name</label>
    		<div class="col-sm-10">
    			<input type="text" name="lastName" id="f-lastName" value="<?php echo $lastName;?>" />
    			<span class="error"><?php echo $lastNameError; ?></span>
    		</div>
    	</div>
    	
    	
    	
    	<div class="form-group">
    		<label class="control-label col-sm-2" for="f-birthDate">Date of Birth</label>
    		<div class="col-sm-10">
    			<input type="date" name="birthDate" id="f-birthDate" value="<?php echo $birthDate;?>" />
    			<span class="error"><?php echo $dobError; ?></span>
    		</div>
    	</div>
    	
    	
    	
		<div class="row show-grid FloatInput">
    		<div class="form=group">
        		<label class="control-label col-sm-2" for="f-phoneArea">Area Code/Phone#</label>
        		<div class="col-sm-1">
        			<input type="number" name="phoneArea" class="PhoneMedium" id="f-phoneArea" placeholder="###" value="<?php echo $phoneArea;?>" />
        		</div>
        		<div class="col-sm-2">
        			<input type="number" name="phoneNum" class="PhoneLarge" placeholder="#######" value="<?php echo $phoneNum;?>" />
        		</div>
        		<div class="col-sm-3"><span class="error"><?php echo $phoneError; ?></span></div>
    		</div>
    	</div>
    	
		<div class="form=group">
			<div class="col-sm-5 col-sm-offset-1">
        		<button type="submit" title="Save Registration" class="ButtonFont">
        			<span class="glyphicon glyphicon-cloud-upload"></span>
        		</button>
        	</div>
        	<div class="col-sm-5">
        		<button type="reset" title="Reset Form Fields" class="ButtonFont">
        			<span class="glyphicon glyphicon-refresh"></span>
        		</button>        		
			</div>
		</div>
				
		<div class="col-sm-5 col-sm-offset-1">
    		<p class="ButtonText">Save</p>
    	</div>
    	<div class="col-sm-5">
			<p class="ButtonText">Reset</p>
		</div>
    </form>
</div>

<?php require_once 'footer.php';?>
</body>
</html>