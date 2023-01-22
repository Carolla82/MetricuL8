<?php
    error_reporting(E_ALL ^ E_NOTICE);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title> Enrollments Page </title>
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
    
    $removeError        = "";
    $addError           = "";
    
    if (isset($_POST["Add"]) && is_null($_POST['allAvailable']))
    {
        $anyErr     = true;
        $addError   = "Choose Class";
    }
    
    if (isset($_POST["Remove"]) && is_null($_POST['allEnrolled']))
    {
        $anyErr         = true;
        $removeError    =   "Choose Class";
    }
    
    if (!$anyErr)
    {
        $dbStore = new MetricuL8Store();
        
        if (isset($_POST['Remove'])) {
            $enrollmentId = $_POST['allEnrolled'];
            
            $dbStore->removeStudent($enrollmentId);
            
            $removeError = 'Enrollment Removed';
        }
        else {
            $studentId = $dbStore->getUserId($_SESSION['username']);
            $classId = $_POST['allAvailable'];
            
            $addResult = $dbStore->enrollStudent($classId, $studentId);
            
            if ($addResult == 'W'){
                $addError = '-- Placed on wait list --';
            }
            else {
                $addError = 'Student Enrolled';
            }
        }
    }
}

$dbStore2 = new MetricuL8Store();
$userId = $dbStore2->getUserId($_SESSION['username']);

// generate list of enrolled classes
$selectedEnrollments = "";

if ($_SERVER["REQUEST_METHOD"]=="POST" 
    && !is_null($_POST['allEnrolled'])) {
    $selectedEnrollments = $_POST['allEnrolled'];
}

if($cResult = $dbStore2->getEnrollments($userId)){
    foreach ($cResult as $row){
        if ($row['id'] == $selectedEnrollments)
        {
            $enrollOptionList   .= '<option value="' . $row['id'] 
                                . '" selected="selected">' . $row['description'] 
                                . '</option>';
        } else {
            $enrollOptionList .= '<option value="' . $row['id'] 
                        . '">' . $row['description'] . '</option>';
        }
    }
    
} else {
    $enrollOptionList = '';
}

$selectedAdds = "";

if ($_SERVER["REQUEST_METHOD"]=="POST"
    && !is_null($_POST['allAvailable'])) {
        $selectedAdds = $_POST['allAvailable'];
    }

if($dResult = $dbStore2->getAvailable($userId)){
    foreach ($dResult as $row){
        if ($row['id'] == $selectedAdds)
        {
            $addOptionList   .= '<option value="' . $row['id']
            . '" selected="selected">' . $row['description']
            . '</option>';
        } else {
            $addOptionList .= '<option value="' . $row['id']
            . '">' . $row['description'] . '</option>';
        }
    }
    
} else {
    $addOptionList = '';
}

?>

<div class="container">
	<h1>Maintain Enrollments</h1>	
	
    <form class="form-horizontal" role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        
    	<div class="form-group">
			<div class="col-sm-5">
                <select name="allEnrolled" id="enrollSel" size="5">
                	<option disabled>Enrolled Classes:</option>
                	<?php echo $enrollOptionList; ?>
                </select>
            </div>
            <div class="col-sm-2">
            	<input type="submit" value="Add" name="Add" />
            </div>
        	<div class="form-group">
    			<div class="col-sm-5">
                    <select name="allAvailable" id="availSel" size="5">
                    	<option disabled>Available Classes:</option>
                    	<?php echo $addOptionList; ?>
                    </select>
                </div>
        	</div>            
    	</div>
    	<div class="form-group">
    		<div class="col-sm-5">
    			&nbsp;
    		</div>
            <div class="col-sm-2">
            	<input type="submit" value="Remove" name="Remove" />
            </div>
            <div class="col-sm-5">
            	&nbsp;
            </div>
        </div>
    	<div class="form-group">
        	<div class="col-sm-6">
        		<span class="error"><?php echo $removeError; ?></span>
        	</div>
        	<div class="col-sm-6">
        		<span class="error"><?php echo $addError; ?></span>
        	</div>
    	</div>
    </form>
</div>

<?php require_once 'footer.php';?>
</body>
</html>