<?php 
include ("basic.php");

$dbhandle = new mysqli('localhost','root','','test1');

$today = getdate();
$month = ($today['month']);
$year = ($today['year']);

$ar = array($year,$month);
$new = implode("-",$ar);
$End = date_create_from_format('Y-F', $new)->format('Y-m'); //july -> 07

$check = "SELECT * FROM egraf WHERE userid = '$_SESSION[userid]'"; // an exo data
$res = mysqli_query($conn,$check);
if (mysqli_num_rows($res)>0) {

###### a erotima #######

$query1 = "SELECT SUM(CASE WHEN type = 'IN_VEHICLE' THEN 1 ELSE 0 END) AS num_ve, SUM(CASE WHEN type = 'RUNNING' THEN 1 ELSE 0 END) AS num_ru, SUM(CASE WHEN type = 'WALKING' THEN 1 ELSE 0 END) AS num_wa, SUM(CASE WHEN type = 'ON_BICYCLE' THEN 1 ELSE 0 END) AS num_bi, SUM(CASE WHEN type = 'ON_FOOT' THEN 1 ELSE 0 END) AS num_fo FROM egraf WHERE SUBSTRING(datetime,1,7) = '$End' AND userid = '$_SESSION[userid]' "; 
$res1 = $dbhandle->query($query1);

foreach($res1 as $r1) {
	  
      $num_ve = (int) $r1['num_ve'];
	  $num_wa = (int) $r1['num_wa']; 
      $num_bi = (int) $r1['num_bi']; 
      $num_ru = (int) $r1['num_ru']; 
      $num_fo = (int) $r1['num_fo']; 

}

$num_eco = $num_wa + $num_bi +$num_ru + $num_fo;

if($num_ve + $num_eco == 0){
	
	$eco_score = 0;
}
else{

$eco_score = intval(($num_eco / ($num_ve + $num_eco))*100);
}

echo "<div class='container'><h4>Your <b><i>eco-score</b></i> for the current month ($month) is: ", $eco_score, "% </h4></div>";

$sql = "UPDATE user SET score = '$eco_score' WHERE userid = '$_SESSION[userid]'";
$conn->query($sql);

##### line chart #####

if($month == 'December'){
	$year_for_12 = $year;
	$month_for_12 = 'January';
	$ar1 = array($year_for_12,$month_for_12);
	$new1 = implode("-",$ar1);  //2019-September
	$Start = date_create_from_format('Y-F', $new1)->format('Y-m'); //2019-9
}
else{ 
	$year_for_12 = $year - 1;
	$number = date('m',strtotime($month)); // march -> 3
	$month_for_12 = $number + 1;
	$ar1 = array($year_for_12,$month_for_12);
	$new1 = implode("-",$ar1);
	$Start = date_create_from_format('Y-n', $new1)->format('Y-m'); // 2019-3 -> 2019-03
}
$query3 = "SELECT MONTH(datetime) as monthname, SUM(CASE WHEN type = 'IN_VEHICLE' THEN 1 ELSE 0 END) AS num_ve, SUM(CASE WHEN type = 'RUNNING' THEN 1 ELSE 0 END) AS num_ru, SUM(CASE WHEN type = 'WALKING' THEN 1 ELSE 0 END) AS num_wa, SUM(CASE WHEN type = 'ON_BICYCLE' THEN 1 ELSE 0 END) AS num_bi, SUM(CASE WHEN type = 'ON_FOOT' THEN 1 ELSE 0 END) AS num_fo FROM egraf WHERE SUBSTRING(datetime,1,7) BETWEEN '$Start' AND '$End' AND userid = '$_SESSION[userid]' GROUP BY monthname ORDER BY datetime"; 
$res3 = $dbhandle->query($query3);
$count = mysqli_num_rows($res3);

$MM = array();
$num_ve = array();
$num_wa = array();
$num_ru = array();
$num_bi = array();
$num_fo = array();

if ($count == "0") {
    $MM = 0;
	echo "<br>";	
	echo "<div class='container'><div class=\"alert alert-danger\">There are <b>no records</b> during the last 12 months for <i>eco-scrore graph</i> to be depicted.</div></div>";
  }

else {

foreach($res3 as $r3) {
	 
	 $month_name = date("F", mktime(0, 0, 0, $r3['monthname'], 10)); 
	 array_push($MM,$month_name);
	 array_push($num_ve,$r3['num_ve']);
	 array_push($num_wa,$r3['num_wa']);
	 array_push($num_ru,$r3['num_ru']);
	 array_push($num_bi,$r3['num_bi']);
	 array_push($num_fo,$r3['num_fo']);
	 
}
?>
<html>
  <head>
  <style>
  @media only screen and (min-width: 992px) {
  .curve {
	  
	height:500px;
	width:1500px;
	margin-right: auto;
	margin-left: auto; 	
  
  }
   
}  

@media only screen and (max-width: 600px) {
  .curve {
	  
	height:300px;
	width:370px;
 	
  
  }
}
  </style>
  <body>
  <div id="curve_chart" class = "curve"></div>
  </body>
</html>

<?php
}

##### b erotima #####

$query2 = "SELECT MIN(datetime) as mindate, MAX(datetime) as maxdate FROM egraf WHERE userid = '$_SESSION[userid]'"; 
$res2 = $dbhandle->query($query2);

foreach($res2 as $r2) {

      $mindate = (string) $r2['mindate']; 
	  $maxdate = (string) $r2['maxdate'];

}

$format = 'Y-m-d H:i:s';
$date1 = DateTime::createFromFormat($format, $mindate);
$date2 = DateTime::createFromFormat($format, $maxdate);
$diff = date_diff($date1,$date2);
echo "<div class='container'><h4>The <b><i>period covered</b></i> by your records is: ", $diff->format("%a day(s) and %h hour(s)"), ".</h4></div><br>";


##### c erotima #####

$query4 = "SELECT last_upload FROM user WHERE userid = '$_SESSION[userid]'"; 
$res4 = $dbhandle->query($query4);

	foreach($res4 as $r4) {
	  
      $last_upload = (string) $r4['last_upload']; 
}

echo "<div class='container'><h4>Your <b><i> last upload date </b></i> is: ", $last_upload, ".</h4> </div><br>";


##### d erotima #####

$query5 = "SELECT fname,lname,score,userid FROM user ORDER BY score DESC";
$res5 = $dbhandle->query($query5);
$onomaep = array();
$thesi = array();
$score = array();
$userid = array();
$i=1;
$stop = '.';
$keep=4; // bold this part in leaderboard (4os user)
foreach($res5 as $r5) {
	 
	  array_push($thesi,$i); 
	  array_push($userid,$r5['userid']);
   	  $onoma = (string) $r5['fname']; 
	  $eponimo = (string) $r5['lname']; 
	  $first_letter = mb_substr($eponimo,0,1,"UTF-8");
	  $c = $first_letter."".$stop; //M.
	  $arr = $onoma." ".$c; // Kostis M.
	  array_push($onomaep,$arr);
  	  $sco = (int) $r5['score']; 
	  array_push($score,$sco);
	  
	  $i=$i+1;
}

if(count($userid) == 1){
	
	$keep=3; // one user in database
	$leader_thesi = array_slice($thesi,0,1);
	$leader_onoma = array_slice($onomaep,0,1);
	$leader_score = array_slice($score,0,1);
		
}

elseif(count($userid) == 2){
	
	for($ki = 0; $ki <= 1; $ki++) {
		if($_SESSION['userid'] == $userid[$ki]){
	
			$keep = $ki+5; // two users in database - two cases
		}	
	}
	$leader_thesi = array_slice($thesi,0,2);
	$leader_onoma = array_slice($onomaep,0,2);
	$leader_score = array_slice($score,0,2);
		
}

else{
	
for($k = 0; $k <= 2; $k++) {
if($_SESSION['userid'] == $userid[$k]){
	
	$keep = $k; // orizei to bold an eimai sthn triada
	}	
}

if($_SESSION['userid'] == $userid[0] OR $_SESSION['userid'] == $userid[1] OR $_SESSION['userid'] == $userid[2]){
	
	$leader_thesi = array_slice($thesi,0,3);
	$leader_onoma = array_slice($onomaep,0,3);
	$leader_score = array_slice($score,0,3);
	
}

else{
	
	for($j = 0; $j <= count($userid)-1; $j++) {
		
		if($userid[$j] == $_SESSION['userid']){
			$temp = $j;
		}
	}
		
	$leader_thesi = array_slice($thesi,0,3);
	$leader_onoma = array_slice($onomaep,0,3);
	$leader_score = array_slice($score,0,3);
	array_push($leader_thesi,$temp+1);
	array_push($leader_onoma,$onomaep[$temp]);
	array_push($leader_score,$score[$temp]);
	
	
}

}
if($keep == 4){
?>
<style>
.table_style tr:nth-child(1) td:nth-child(1) {
background: #FFD700;
}
.table_style tr:nth-child(1) td:nth-child(2) {
background: #FFD700;
}
.table_style tr:nth-child(1) td:nth-child(3) {
background: #FFD700;
}
.table_style tr:nth-child(2) {
background: #C0C0C0;
}
.table_style tr:nth-child(3) {
background: #B8860B;
}
.table_style tr:nth-child(4) {
 font-weight: bold;
 font-size: 15px;
}
.bold-font {
font-weight: bold;
}
.large-font {
font-size: 15px;
}
.background {
background-color: #4169E1;
}
.left-text {
text-align: left;
}

</style>
<?php 

}
if($keep == 0){

?>
<style>
.table_style tr:nth-child(1) td:nth-child(1) {
background: #FFD700;
font-weight: bold;
font-size: 15px;
}
.table_style tr:nth-child(1) td:nth-child(2) {
background: #FFD700;
font-weight: bold;
font-size: 15px;
}
.table_style tr:nth-child(1) td:nth-child(3) {
background: #FFD700;
font-weight: bold;
font-size: 15px;
}
.table_style tr:nth-child(2) {
background: #C0C0C0;
}
.table_style tr:nth-child(3) {
background: #B8860B;
}
.bold-font {
font-weight: bold;
}
.large-font {
font-size: 15px;
}
.background {
background-color: #4169E1;
}
.left-text {
text-align: left;
}
</style>
<?php
}
if($keep == 1){

?>
<style>
.table_style tr:nth-child(1) td:nth-child(1) {
background: #FFD700;
}
.table_style tr:nth-child(1) td:nth-child(2) {
background: #FFD700;
}
.table_style tr:nth-child(1) td:nth-child(3) {
background: #FFD700;
}
.table_style tr:nth-child(2) {
background: #C0C0C0;
font-weight: bold;
font-size: 15px;
}
.table_style tr:nth-child(3) {
background: #B8860B;
}
.bold-font {
font-weight: bold;
}
.large-font {
font-size: 15px;
}
.background {
background-color: #4169E1;
}
.left-text {
text-align: left;
}
</style>
<?php
}
if($keep == 2){

?>
<style>
.table_style tr:nth-child(1) td:nth-child(1) {
background: #FFD700;
}
.table_style tr:nth-child(1) td:nth-child(2) {
background: #FFD700;
}
.table_style tr:nth-child(1) td:nth-child(3) {
background: #FFD700;
}
.table_style tr:nth-child(2) {
background: #C0C0C0;
}
.table_style tr:nth-child(3) {
background: #B8860B;
font-weight: bold;
font-size: 15px;
}
.bold-font {
font-weight: bold;
}
.large-font {
font-size: 15px;
}
.background {
background-color: #4169E1;
}
.left-text {
text-align: left;
}
</style>
<?php
}

if($keep == 3){

?>
<style>
.table_style tr:nth-child(1) td:nth-child(1) {
background: #FFD700;
font-weight: bold;
font-size: 15px;
}
.table_style tr:nth-child(1) td:nth-child(2) {
background: #FFD700;
font-weight: bold;
font-size: 15px;
}
.table_style tr:nth-child(1) td:nth-child(3) {
background: #FFD700;
font-weight: bold;
font-size: 15px;
}
.bold-font {
font-weight: bold;
}
.large-font {
font-size: 15px;
}
.background {
background-color: #4169E1;
}
.left-text {
text-align: left;
}
</style>
<?php
}
if($keep == 5){

?>
<style>
.table_style tr:nth-child(1) td:nth-child(1) {
background: #FFD700;
font-weight: bold;
font-size: 15px;
}
.table_style tr:nth-child(1) td:nth-child(2) {
background: #FFD700;
font-weight: bold;
font-size: 15px;
}
.table_style tr:nth-child(1) td:nth-child(3) {
background: #FFD700;
font-weight: bold;
font-size: 15px;
}
.table_style tr:nth-child(2) {
background: #C0C0C0;
}
.bold-font {
font-weight: bold;
}
.large-font {
font-size: 15px;
}
.background {
background-color: #4169E1;
}
.left-text {
text-align: left;
}
</style>
<?php
}
if($keep == 6){

?>
<style>
.table_style tr:nth-child(1) td:nth-child(1) {
background: #FFD700;
}
.table_style tr:nth-child(1) td:nth-child(2) {
background: #FFD700;
}
.table_style tr:nth-child(1) td:nth-child(3) {
background: #FFD700;
}
.table_style tr:nth-child(2) {
background: #C0C0C0;
font-weight: bold;
font-size: 15px;
}
.bold-font {
font-weight: bold;
}
.large-font {
font-size: 15px;
}
.background {
background-color: #4169E1;
}
.left-text {
text-align: left;
}
</style>
<?php
}
?>
<body>
<h4 style="margin-left:40%; font-weight: bold;">Eco-score LEADERBOARD</h4>
<div style= "margin-left:15%;">
<div id="table_div" class="table_style"></div>
</div>
<br>
<br>
</body>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
  	  google.charts.load('current', {'packages':['table']});
  	  google.charts.setOnLoadCallback(drawTable);

      function drawChart() {
		  
		var month = <?php echo json_encode($MM); ?> ;
		var num_ve = <?php echo json_encode($num_ve); ?> ;
		var num_wa = <?php echo json_encode($num_wa); ?> ;
		var num_ru = <?php echo json_encode($num_ru); ?> ;
		var num_fo = <?php echo json_encode($num_fo); ?> ;
		var num_bi = <?php echo json_encode($num_bi); ?> ;
		var num_eco = [];
		var point = [['Month' , 'Eco-score (%)']];
		for (var i = 0 ; i < month.length; i++){
			
			num_eco[i] = parseInt(num_wa[i]) + parseInt(num_ru[i]) + parseInt(num_bi[i]) + parseInt(num_fo[i]); 
			if(num_eco[i] + parseInt(num_ve[i]) == 0){
				
				point.push([ month[i] , 0 ]);
				
			}
			else{
			point.push([ month[i] , ((num_eco[i]) / (num_eco[i] + parseInt(num_ve[i]))) * 100 ]);
			}
		};  
        var data = google.visualization.arrayToDataTable(point);

        var options = {
          title: 'Eco-score for the last 12 months',
          hAxis: {title: 'Months', minValue: 0, maxValue: 15},
          vAxis: {title: 'Eco-score (%)', minValue: 0, maxValue: 15},
          legend: 'none'
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
	  
	  	  var cssClassNames = {
			'tableCell': 'left-text',
			'headerRow': 'background large-font bold-font',


		};
	  
		function drawTable() {
		  
		var thesi = <?php echo json_encode($leader_thesi); ?> ;
		var onoma = <?php echo json_encode($leader_onoma); ?> ;
		var score = <?php echo json_encode($leader_score); ?> ;

		var point = [['Rank' , 'User', 'Eco-score (%)']];
		for (var i = 0; i < thesi.length; i++){
			
			point.push([ thesi[i] , onoma[i] , score[i] ]);

			};
		var data = google.visualization.arrayToDataTable(point);
		var table = new google.visualization.Table(document.getElementById('table_div'));
		
        table.draw(data, {showRowNumber: false, width: '80%', height: '100%', 'cssClassNames': cssClassNames});
      }
    </script>
<?php

}

else{
	
	echo "<div class='container'><div class=\"alert alert-info\">Upload a JSON file <b><u><a href='ImportData.php'>here</a></u></b> so as to keep track of your activity!</div></div>";
	
}

?>