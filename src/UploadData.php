<?php 
session_start();
$conn=mysqli_connect("localhost","root","","test1");
mysqli_query($conn,"set names 'utf8'");

$typ = array();
$conf = array();
$flag = array();

/* $coordinates = array();
$filename = "agosto.json";
$object = json_decode(file_get_contents($filename));   */


if(isset($_POST['coo'])){
$coordinates = json_decode($_POST['coo'],true); // array
$object = json_decode($_POST['file']); // object
}

foreach($object->locations as $mydata) { 
	if (isset($mydata->activity)) {
        foreach($mydata->activity as $activity) {	
			foreach($activity->activity as $new){		   
				
				if ($new->type == "EXITING_VEHICLE") {
					$activity_type = "IN_VEHICLE"; 
				}
				elseif ($new->type == "IN_FOUR_WHEELER_VEHICLE"){
					$activity_type = "IN_VEHICLE"; 
				}
				elseif ($new->type == "IN_TWO_WHEELER_VEHICLE"){
					$activity_type = "IN_VEHICLE";
				}
				elseif ($new->type == "IN_RAIL_VEHICLE"){
					$activity_type = "IN_VEHICLE"; 			
				}					
				elseif ($new->type == "IN_ROAD_VEHICLE"){
					$activity_type = "IN_VEHICLE"; 
				}
				elseif ($new->type == "IN_BUS"){
					$activity_type = "IN_VEHICLE"; 
				}
				elseif ($new->type == "IN_CAR"){
					$activity_type = "IN_VEHICLE"; 
				}
				else {
					$activity_type = $new->type;	
				}					
			    $activity_confidence = intval($new->confidence);
				array_push($typ, $activity_type);			
				array_push($conf, $activity_confidence);
			}
			// polla confidence
			if(count($conf) >= 2) {
						
				for ($x = 0; $x <= count($conf)-1; $x++) {

					if($conf[$x] == max($conf)) {
						
						$temp = $x;
																														
					}
				
				} 
				
				$latitudeE7 = $mydata->latitudeE7 / 10000000 . "\n";
				$longitudeE7 = $mydata->longitudeE7 / 10000000 . "\n";
				$STATE = 0;
				if(($latitudeE7>=38.141050 AND $latitudeE7<=38.321493) AND ($longitudeE7<=21.867434 AND $longitudeE7>=21.638781)) {
					// an einai adeio??
					if(!empty($coordinates)){
						for($i = 0; $i <= count($coordinates)-1; $i++) {
							//an einai mesa
							if(($latitudeE7<$coordinates[$i][0] AND $latitudeE7>$coordinates[$i][2]) AND ($longitudeE7<$coordinates[$i][1] AND $longitudeE7>$coordinates[$i][3])){
								$STATE = 1;
								
							}
						}
						
						if($STATE == 0){
								
							$type = $typ[$temp];
							$confi = $conf[$temp];
							$activity_timestampMs = $activity->timestampMs . "\n";
							$activity_date = date('Y/m/d H:i:s', (int)$activity_timestampMs/1000);
							$date_time = date('Y/m/d H:i:s', (int)$activity_timestampMs/1000);
							$accuracy = $mydata->accuracy;
					
							if (isset($mydata->velocity)) {
								$velocity = $mydata->velocity;
							}
							else {
								$velocity = "null";
							}
							if (isset($mydata->heading)) {
								$heading = $mydata->heading;
							}
							else {
								$heading = "null";
							}
							if ((isset($mydata->altitude))){
								$altitude = $mydata->altitude;
							}
							else {
								$altitude = "null";
							}
							if (isset($mydata->verticalAccuracy)){
								$verticalAccuracy = $mydata->verticalAccuracy;
							}
							else {
								$verticalAccuracy = "null";
							}
										
							$sql11 = "INSERT INTO egraf (id, datetime, heading, verticalAccuracy, velocity, altitude, latitude, longitude, accuracy, type, confidence, userid) VALUES(NULL,'$date_time', '$heading', '$verticalAccuracy', '$velocity', '$altitude', $latitudeE7, $longitudeE7, $accuracy, '$type', '$confi', '$_SESSION[userid]')";
							if ($conn->query($sql11) === TRUE) {
					
								array_push($flag,1);
							
							} 
							else {
					
								array_push($flag,2);

							}
						}
							
					}	
					//else gia to coordinates
					else{
										
					$type = $typ[$temp];
					$confi = $conf[$temp];
					$activity_timestampMs = $activity->timestampMs . "\n";
					$activity_date = date('Y/m/d H:i:s', (int)$activity_timestampMs/1000);
					$date_time = date('Y/m/d H:i:s', (int)$activity_timestampMs/1000);
					$accuracy = $mydata->accuracy;
					
					if (isset($mydata->velocity)) {
						$velocity = $mydata->velocity;
					}
					else {
						$velocity = "null";
					}
					if (isset($mydata->heading)) {
						$heading = $mydata->heading;
					}
					else {
						$heading = "null";
					}
					if ((isset($mydata->altitude))){
						$altitude = $mydata->altitude;
					}
					else {
						$altitude = "null";
					}
					if (isset($mydata->verticalAccuracy)){
						$verticalAccuracy = $mydata->verticalAccuracy;
					}
					else {
						$verticalAccuracy = "null";
					}
										
					$sql12 = "INSERT INTO egraf (id, datetime, heading, verticalAccuracy, velocity, altitude, latitude, longitude, accuracy, type, confidence, userid) VALUES(NULL,'$date_time', '$heading', '$verticalAccuracy', '$velocity', '$altitude', $latitudeE7, $longitudeE7, $accuracy, '$type', '$confi', '$_SESSION[userid]')";
					if ($conn->query($sql12) === TRUE) {
					
						array_push($flag,1);
					} 
					else {
					
						array_push($flag,2);

					}
					
					}					
									
				}
								
			}
			
								
			//count = 1 diladi ena confidence
			else {				
			
				$latitudeE7 = $mydata->latitudeE7 / 10000000 . "\n";
				$longitudeE7 = $mydata->longitudeE7 / 10000000 . "\n";
				$STATE = 0;
				if(($latitudeE7>=38.141050 AND $latitudeE7<=38.321493) AND ($longitudeE7<=21.867434 AND $longitudeE7>=21.638781)) {
					// an einai adeio??
					if(!empty($coordinates)){
						for($i = 0; $i <= count($coordinates)-1; $i++) {
							//an einai mesa
							if(($latitudeE7<$coordinates[$i][0] AND $latitudeE7>$coordinates[$i][2]) AND ($longitudeE7<$coordinates[$i][1] AND $longitudeE7>$coordinates[$i][3])){
								
								$STATE = 1;
								
							}
						}
						
						if($STATE == 0){
								
							$type = $activity_type;
							$confi = $activity_confidence;
							$activity_timestampMs = $activity->timestampMs . "\n";
							$activity_date = date('Y/m/d H:i:s', (int)$activity_timestampMs/1000);
							$date_time = date('Y/m/d H:i:s', (int)$activity_timestampMs/1000);
							$accuracy = $mydata->accuracy;
					
							if (isset($mydata->velocity)) {
								$velocity = $mydata->velocity;
							}
							else {
								$velocity = "null";
							}
							if (isset($mydata->heading)) {
								$heading = $mydata->heading;
							}
							else {
								$heading = "null";
							}
							if ((isset($mydata->altitude))){
								$altitude = $mydata->altitude;
							}
							else {
								$altitude = "null";
							}
							if (isset($mydata->verticalAccuracy)){
								$verticalAccuracy = $mydata->verticalAccuracy;
							}
							else {
								$verticalAccuracy = "null";
							}
										
							$sql21 = "INSERT INTO egraf (id, datetime, heading, verticalAccuracy, velocity, altitude, latitude, longitude, accuracy, type, confidence, userid) VALUES(NULL,'$date_time', '$heading', '$verticalAccuracy', '$velocity', '$altitude', $latitudeE7, $longitudeE7, $accuracy, '$type', '$confi', '$_SESSION[userid]')";
							if ($conn->query($sql21) === TRUE) {
					
								array_push($flag,1);
							
							} 
							else {
					
								array_push($flag,2);

							}
						}
							
					}
				//coordinates
				else{ 
					$type = $activity_type;
					$confi = $activity_confidence;
					$activity_timestampMs = $activity->timestampMs . "\n";
					$activity_date = date('Y/m/d H:i:s', (int)$activity_timestampMs/1000);
					$date_time = date('Y/m/d H:i:s', (int)$activity_timestampMs/1000);
					$accuracy = $mydata->accuracy;
					
					if (isset($mydata->velocity)) {
						$velocity = $mydata->velocity;
					}
					else {
						$velocity = "null";
					}
					if (isset($mydata->heading)) {
						$heading = $mydata->heading;
					}
					else {
						$heading = "null";
					}
					if ((isset($mydata->altitude))){
						$altitude = $mydata->altitude;
					}
					else {
						$altitude = "null";
					}
					if (isset($mydata->verticalAccuracy)){
						$verticalAccuracy = $mydata->verticalAccuracy;
					}
					else {
						$verticalAccuracy = "null";
					}
								
					$sql22 = "INSERT INTO egraf (id, datetime, heading, verticalAccuracy, velocity, altitude, latitude, longitude, accuracy, type, confidence, userid) VALUES(NULL,'$date_time', '$heading', '$verticalAccuracy', '$velocity', '$altitude', $latitudeE7, $longitudeE7, $accuracy, '$type', '$confi', '$_SESSION[userid]')";
					if ($conn->query($sql22) === TRUE) {
					
						array_push($flag,1);
				
					} 
					else {
						array_push($flag,2);

					}
				}				
			}
			}
									
			$typ = array();
			$conf = array();			
         	
	}
	
	}

//xoris activity
else {
		$latitudeE7 = $mydata->latitudeE7 / 10000000 . "\n";
		$longitudeE7 = $mydata->longitudeE7 / 10000000 . "\n";
		$STATE = 0;
		// mesa sthn patra
		if(($latitudeE7>=38.141050 AND $latitudeE7<=38.321493) AND ($longitudeE7<=21.867434 AND $longitudeE7>=21.638781)) {
		// an einai adeio??
			if(!empty($coordinates)){
				for($i = 0; $i <= count($coordinates)-1; $i++) {
					//an einai mesa
					if(($latitudeE7<$coordinates[$i][0] AND $latitudeE7>$coordinates[$i][2]) AND ($longitudeE7<$coordinates[$i][1] AND $longitudeE7>$coordinates[$i][3])){
								
						$STATE = 1;
								
					}
					
				}
				
				if($STATE == 0){
								
					$type = "null";
					$confi = "null";
					$timestampMs = $mydata->timestampMs . "\n";
					$date_time = date('Y/m/d H:i:s', (int)$timestampMs/1000);
					$accuracy = $mydata->accuracy;
					
					if (isset($mydata->velocity)) {
						$velocity = $mydata->velocity;
					}
					else {
						$velocity = "null";
					}
					if (isset($mydata->heading)) {
						$heading = $mydata->heading;
					}
					else {
						$heading = "null";
					}
					if ((isset($mydata->altitude))){
						$altitude = $mydata->altitude;
					}
					else {
						$altitude = "null";
					}
					if (isset($mydata->verticalAccuracy)){
						$verticalAccuracy = $mydata->verticalAccuracy;
					}
					else {
						$verticalAccuracy = "null";
					}
										
					$sql31 = "INSERT INTO egraf (id, datetime, heading, verticalAccuracy, velocity, altitude, latitude, longitude, accuracy, type, confidence, userid) VALUES(NULL,'$date_time', '$heading', '$verticalAccuracy', '$velocity', '$altitude', $latitudeE7, $longitudeE7, $accuracy, '$type', '$confi', '$_SESSION[userid]')";
					if ($conn->query($sql31) === TRUE) {
					
						array_push($flag,1);
							
					} 
					else {
					
						array_push($flag,2);

					}
				}
							
			}
		//coordinates einai adeio
		else{ 
			
			$type = "null";
			$confi = "null"; // varchar sti vasi
			$timestampMs = $mydata->timestampMs . "\n";
			$date_time = date('Y/m/d H:i:s', (int)$timestampMs/1000);
			$accuracy = $mydata->accuracy;
			
			if (isset($mydata->velocity)) {
				$velocity = $mydata->velocity;
			}
			else {
				$velocity = "null";
			}
			if (isset($mydata->heading)) {
				$heading = $mydata->heading;
			}
			else {
				$heading = "null";
			}
			if ((isset($mydata->altitude))){
				$altitude = $mydata->altitude;
			}
			else {
				$altitude = "null";
			}
			if (isset($mydata->verticalAccuracy)){
				$verticalAccuracy = $mydata->verticalAccuracy;
			}
			else {
				$verticalAccuracy = "null";
			}
		
			$sql32 = "INSERT INTO egraf (id, datetime, heading, verticalAccuracy, velocity, altitude, latitude, longitude, accuracy, type, confidence, userid) VALUES(NULL,'$date_time', '$heading', '$verticalAccuracy', '$velocity', '$altitude', $latitudeE7, $longitudeE7, $accuracy, '$type', '$confi', '$_SESSION[userid]')";
			if ($conn->query($sql32) === TRUE) {
					
				array_push($flag,1);
				
			} 
			else {
				array_push($flag,2);

			}
		}
	
	}
	
}

}
if(!empty($flag)){
if(max($flag) == 1){
	
 	$today = getdate();
	$mday = $today['mday'];
	$month = $today['month'];
	$year = $today['year'];
	$ar = array($mday,$month,$year);
	$new = implode("-",$ar);
	$upload_date = date('Y-m-d', strtotime($new));

	$sql4 = "UPDATE user SET last_upload = '$upload_date' WHERE userid = '$_SESSION[userid]'";
	$conn->query($sql4); 
	$value =  array('msg' => 'true' );
	//echo "<div class='container'><div class=\"alert alert-success\">Your file has been successfully uploaded.</div></div>";

}
else{
	
	$value =  array('msg' => 'false' );
	//echo "<div class='container'><div class=\"alert alert-danger\">There was an error uploading your file.</div></div>";
	//echo "Error: " . $sql3 . "<br>" . $conn->error;

}
echo json_encode($value);
}

else {
		$value =  array('msg' => 'true' );
		echo json_encode($value);	
}
?>  

