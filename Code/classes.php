<?php
    error_reporting(E_ALL ^ E_NOTICE);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title> Classes Page </title>
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
    
    $deleteError        = "";
    $descriptionError   = "";
    $yearError          = "";
    $semesterError      = "";
    $maxEnrollmentError = "";
    $addError           = "";
    
    
    // set from input form
    if (!empty($_POST["description"]))
    {
        $description = $_POST["description"];
    }
   
    if (!empty($_POST["year"]))
    {
        $year = $_POST["year"];
    }
    
    if (!empty($_POST["semester"]))
    {
        $semester = $_POST["semester"];
    }
    
    if (!empty($_POST["maxEnrollment"])) 
    {
        $maxEnrollment = $_POST["maxEnrollment"];
    }
    
    if (isset($_POST["Delete"]) && is_null($_POST['allClass']))
    {
        $anyErr = true;
        $deleteError = "Choose Class";
    }
    
    if (!isset($_POST["Delete"]))
    {
        if (empty($_POST["description"])) {
            $anyErr = true;
            $descriptionError = "Description required";
        }
        
        if (empty($_POST["year"])) {
            $anyErr = true;
            $yearError = "Year required";
        }
        
        if (empty($_POST["semester"])) {
            $anyErr = true;
            $semesterError = "Semester required";
        }
        
        if (empty($_POST["maxEnrollment"])) {
            $anyErr = true;
            $maxEnrollmentError = "Max Enrollment required";
        }
    }
    
    if ($anyErr)
    {
        $errorsExist = "Fix the errors below";
    } else {
        
        $dbStore = new MetricuL8Store();
        
        if (isset($_POST["Delete"]))
        {
            foreach ($_POST['allClass'] as $theClass)
            {
                $dbStore->cancelClass($theClass);
            }
            $errorsExist = "Class(es) Removed.";
        } else {
            $dbStore->addclass($description, $year, $semester, $maxEnrollment);
            $errorsExist = "Class Added!";
        }
        
        $description   = "";
        $year          = "";
        $semester      = "";
        $maxEnrollment = "";
        
        $deleteError        = "";
        $descriptionError   = "";
        $yearError          = "";
        $semesterError      = "";
        $maxEnrollmentError = "";
        $addError           = "";
    }
}

// generate list of classes into $optionList
$dbStore2 = new MetricuL8Store();

$selectedClasses = array();

if ($_SERVER["REQUEST_METHOD"]=="POST"
    && !is_null($_POST['allClass']))
{
    $selectedClasses = $_POST['allClass'];
}

if($cResult = $dbStore2->getClassList()){
    foreach ($cResult as $row){
        if (in_array($row['id'], $selectedClasses))
        {
            $optionList .= '<option value="' . $row['id'] . '" selected="selected">' . $row['description'] . '</option>';
        } else {
            $optionList .= '<option value="' . $row['id'] . '">' . $row['description'] . '</option>';
        }
    }
    
} else {
    $optionList = '';
}

?>

<div class="container">
	<h1>New Class</h1>
	<p class="error"><?php echo $errorsExist;?></p>
    <form class="form-horizontal" role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        
    	<div class="form-group">
    		<label class="control-label col-sm-2" for="f-description">Description</label>
    		<div class="col-sm-10">
    			<input type="text" name="description" id="f-description" value="<?php echo $description;?>" />
    			<span class="error"><?php echo $descriptionError; ?></span>
    		</div>
    	</div>

    	<div class="form-group">
    		<label class="control-label col-sm-2" for="f-year">Year</label>
    		<div class="col-sm-10">
    			<input type="number" name="year" id="f-year" value="<?php echo $year;?>" />
    			<span class="error"><?php echo $yearError; ?></span>
    		</div>
    	</div>
    	
    	<div class="form-group">
    		<label class="control-label col-sm-2" for="f-semester">Semester</label>
    		<div class="col-sm-10">
    			<input type="number" name="semester" id="f-semester" value="<?php echo $semester;?>" />
    			<span class="error"><?php echo $semesterError; ?></span>
    		</div>
    	</div>
    	
    	<div class="form-group">
    		<label class="control-label col-sm-2" for="f-maxEnrollment">Max Enrollment</label>
    		<div class="col-sm-10">
    			<input type="number" name="maxEnrollment" id="f-maxEnrollment" value="<?php echo $maxEnrollment;?>" />
    			<span class="error"><?php echo $maxEnrollmentError; ?></span>
    		</div>
    	</div>

    	<div class="form-group">
    		<div class="col-sm-6">
    			<input type="submit" value="Add" name="Add" />
    		</div>
        	<div class="col-sm-6">
        		<span class="error"><?php echo $addError; ?></span>
        	</div>    		
    	</div>
    	
    	<div class="col-sm-12">
    		<h1>Cancel Class</h1>
    	</div>
    	<div class="form-group">
			<div class="col-sm-12">
                <select name="allClass[]" multiple id="classSel">
                	<?php echo $optionList; ?>
                </select>
            </div>
    	</div>
    	
        <div class="col-sm-12">
        	<input type="submit" value="Delete" name="Delete" />
        </div>    	
    	
    	<div class="col-sm-12">
    		<span class="error"><?php echo $deleteError; ?></span>
    	</div>    	
    </form>
</div>

<?php require_once 'footer.php';?>
</body>
</html>