<?php

class MetricuL8Store {
    
    function removeStudent($enrollmentId){
        $con='mysql:host=localhost;dbname=metricul8v2;user=EmeraldV;password=goraider$';
        
        $pdo = new PDO($con);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $pdo->query( "CALL removeStudent (" . $enrollmentId . ")" );
        $pdo = null;
    }
    
    function enrollStudent($classId, $studentId) {
        try {
            $con='mysql:host=localhost;dbname=metricul8v2;user=EmeraldV;password=goraider$';
            
            $pdo = new PDO($con);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = "CALL enrollStudent ("
                .$classId . ", " . $studentId
                .")";
            $result = $pdo->query($sql);
            
            $row = $result->fetch();
            $addResult = $row['addResult'];
            
            $pdo = null;
            
            return $addResult;
        }
        catch (PDOException $e) {
            die( $e->getMessage() );
        }
    }
    
    function getUserId($email) {
        $con='mysql:host=localhost;dbname=metricul8v2;user=EmeraldV;password=goraider$';
        
        $pdo = new PDO($con);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = "SELECT MAX(id) AS id FROM student WHERE email='"
            .$email
            ."'";
        
        $result = $pdo->query($sql);

        $row = $result->fetch();
        $id = $row['id'];
        
        $pdo = null;
        
        return $id;
    }
    
    function getAvailable($studentId) {
        try{
            $con='mysql:host=localhost;dbname=metricul8v2;user=EmeraldV;password=goraider$';
            
            $pdo = new PDO($con);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = "SELECT class.Id AS id, concat_ws('-', class.year, class.semester, class.classDescription) AS description FROM class LEFT JOIN enrollment ON class.id = enrollment.classId AND enrollment.studentId = "
                . $studentId 
                ." WHERE enrollment.id IS NULL";
            
            $result = $pdo->query($sql);
            
            $resultSet = array();
            while($row = $result->fetch()) {
                $resultSet[] = $row;
            }
            
            $pdo = null;
            
            return $resultSet;
        }
        catch(PDOException $e){
            die( $e->getMessage() );
        }
    }
    
    function getEnrollments($studentId) {
        try{
            $con='mysql:host=localhost;dbname=metricul8v2;user=EmeraldV;password=goraider$';
            
            $pdo = new PDO($con);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = "SELECT enrollment.Id AS id, concat_ws('-', class.year, class.semester, class.classDescription) AS description FROM class JOIN enrollment ON class.id=enrollment.classId WHERE enrollment.studentId = " . $studentId;
            $result = $pdo->query($sql);
            
            $resultSet = array();
            while($row = $result->fetch()) {
                $resultSet[] = $row;
            }
            
            $pdo = null;
            
            return $resultSet;
        }
        catch(PDOException $e) {
            die( $e->getMessage() );
        }
    }
    
    function addclass($description, $year, $semester, $maxEnrollment) {
        try {
            $con='mysql:host=localhost;dbname=metricul8v2;user=EmeraldV;password=goraider$';
            
            $pdo = new PDO($con);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $result = $pdo->query('SELECT MAX(id)+1 AS maxid FROM class');
            while($row = $result->fetch()) {
                $nextId = $row['maxid'];
            }
            
            $stmt = $pdo->prepare('INSERT INTO class VALUES( :nextId, :description, :year, :semester, :maxEnrollment)');
            $stmt->execute([ 'nextId' => $nextId, 'description' => $description, 'year' => $year, 'semester' => $semester, 'maxEnrollment' => $maxEnrollment ]);
            $pdo = null;

            return;
        }
        catch (PDOException $e) {
            die( $e->getMessage() );
        }
    }
    
    function cancelClass($id) {
        try {
            $con='mysql:host=localhost;dbname=metricul8v2;user=EmeraldV;password=goraider$';
            
            $pdo = new PDO($con);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $result = $pdo->query('SELECT COUNT(*) AS rowCount FROM enrollment WHERE classid='.$id);
            while($row = $result->fetch()) {
                $rowCount1 = $row['rowCount'];
            }
            
            $result = $pdo->query('SELECT COUNT(*) AS rowCount FROM waitlist WHERE classid='.$id);
            while($row = $result->fetch()) {
                $rowCount2 = $row['rowCount'];
            }
            
            if($rowCount1 == 0 && $rowCount2 == 0)
            {
                $sql = 'DELETE FROM class WHERE id='.$id;
                $pdo->exec($sql);
            }            
            
            $pdo = null;
            
            return;
        }
        catch (PDOException $e) {
            die( $e->getMessage() );
        }
    }
    
    function getClassList() {
        try {
            $con='mysql:host=localhost;dbname=metricul8v2;user=EmeraldV;password=goraider$';
            
            $pdo = new PDO($con);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $result = $pdo->query("SELECT id, concat_ws('-', year, semester, classDescription) AS description FROM class ORDER BY id");
            $resultSet = array();
            
            while($row = $result->fetch()) {
                $resultSet[] = $row;
            }
            $pdo = null;
            
            return $resultSet;
        }
        catch (PDOException $e) {
            die( $e->getMessage() );
        }
    }
    
    function executeSelectQuery($sql, $con="mysql:host=localhost;dbname=metricul8v2;user=EmeraldV;password=goraider$") {
        try {
            $pdo = new PDO($con);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $result = $pdo->query($sql);
            
            $resultSet = array();
            
            while($row = $result->fetch()) {
                $resultSet[] = $row;
            }
            $pdo = null;
            
            return $resultSet;
        }
        catch (PDOException $e) {
            die( $e->getMessage() );
        }
    }
    
    function executeQuery($sql, $con="mysql:host=localhost;dbname=metricul8v2;user=EmeraldV;password=goraider$") {
        try {
            $pdo = new PDO($con);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $count = $pdo->exec($sql);
            $pdo = null;
            
            return $count;
        }
        catch (PDOException $e) {
            die( $e->getMessage() );
        }
    }
    
    function checkCred($usernameTry, $passKeyTry) {
        try {
            $con='mysql:host=localhost;dbname=metricul8v2;user=EmeraldV;password=goraider$';
            
            $pdo = new PDO($con);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $pdo->prepare('SELECT COUNT(*) AS stdcount FROM student WHERE email = :usernametry AND password= :passkeytry');
            $stmt->execute([ 'usernametry' => $usernameTry, 'passkeytry' => $passKeyTry ]);
            
            foreach($stmt as $row) {
                $result = $row['stdcount'] == 1;
            }
            $pdo = null;            
            return $result;
        }
        catch (PDOException $e) {
            die( $e->getMessage() );
        }
    }
}

?>