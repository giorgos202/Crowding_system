<?php
include ("basic.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	$uo=0;
} else{
	$uo=1;
	$sql = "TRUNCATE TABLE egraf"; 
	$result = $conn->query($sql);
 
	$sql1 = "TRUNCATE TABLE user"; 
	$result1 = $conn->query($sql1);
}

 if($uo == 1){
	 
	 echo "<div class='container'><div class=\"alert alert-warning\">The DataBase was deleted.</div></div>";
	 
 }
 
 else {
	
	echo "<div class='container'><div class=\"alert alert-danger\">Error due to connection failure.</div></div>";
	
 }
 ?>
