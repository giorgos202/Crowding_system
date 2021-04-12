<?php 
include ("basic.php");
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <meta charset="utf-8">
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDB8Hzdr9qGqPyzhjLJOmXypyPlpQO0fIk&libraries=visualization"
			type="text/javascript"></script>
	
	<script src="https://rawgit.com/abdmob/x2js/master/xml2json.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">

</head>

<style>
.button {
  background-color: #A52A2A;
  border: none;
  color: white;
  padding: 10px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  cursor: pointer;
}

.button1{
border-radius: 12px;
}
</style>

	<div class='container'>
    <form method="post">
        <div class='col-md-3 col-md-push-4 col-xs-6'>
					<br>
					<br>
					<label>From:</label>
                    <input type='text' size="18"  id='startDate' name= 'startDate' value = "<?php if (isset($_POST['startDate'])){ echo  $_POST['startDate']; } ?>" required />
        </div>
        <div class='col-md-4 col-md-push-3 col-xs-6'>
					<br>
					<br>
					<label>To:</label>
                    <input type='text' size="18" id='endDate' name = 'endDate' value = "<?php if (isset($_POST['endDate'])){ echo  $_POST['endDate']; } ?>" required />
        </div>
		<br>
		<div class='col-md-1 col-md-push-1 col-xs-6 col-xs-push-4'>
		<input type="submit" name="search" class="button button1" value="SEARCH" /> 
		</div>
	</div>
	</form>
	
<script>
$(function () {
            $('#startDate').datetimepicker({
			format: 'YYYY-MM',
			maxDate: new Date(),
			useCurrent: false
			});
            $('#endDate').datetimepicker({
			format: 'YYYY-MM',
			maxDate: new Date(),
			useCurrent: false
			});
            $("#startDate").on("dp.change", function (e) {
                $('#endDate').data("DateTimePicker").minDate(e.date);
            });
            $("#endDate").on("dp.change", function (e) {
                $('#startDate').data("DateTimePicker").maxDate(e.date);
            });
        });
</script>
	
<?php 
$dbhandle = new mysqli('localhost','root','','test1');

if (isset($_POST['search'])) {
  
  $Start = $_POST['startDate'];
  $End = $_POST['endDate'];
   
  $query = "SELECT type,count(*) as plithos FROM egraf WHERE SUBSTRING(datetime,1,8) BETWEEN '$Start' AND '$End' AND type <> 'null' AND userid = '$_SESSION[userid]' GROUP BY type"; 
  $query1 = "SELECT SUBSTRING(datetime,11,3) as HourDay, type, COUNT(type) as plithos1 FROM egraf WHERE SUBSTRING(datetime,1,8) BETWEEN '$Start' AND '$End' AND type <> 'null' AND userid = '$_SESSION[userid]' GROUP BY type, HourDay";
  $query2 = "SELECT DAYNAME(datetime) as WeekDay, type, COUNT(type) as plithos2 FROM egraf WHERE SUBSTRING(datetime,1,8) BETWEEN '$Start' AND '$End' AND type <> 'null' AND userid = '$_SESSION[userid]' GROUP BY type, WeekDay";
  $res = $dbhandle->query($query);
  $res1 = $dbhandle->query($query1);
  $res2 = $dbhandle->query($query2);

  
$activ = array();
$num  = array();
foreach($res as $r) {

	  array_push($activ,$r['type']);
	  array_push($num,$r['plithos']);
}

$minutes = '00';
$rows1 = array();
foreach($res1 as $r1) {

      $temp1 = array();	
	  $arr = [$r1['HourDay'],$minutes];
      $arri = implode(':',$arr);	
      $temp1[] = (string) $arri; 
	  $temp1[] = (string) $r1['type']; 
      $temp1[] = (int) $r1['plithos1'];

      $rows1[] = $temp1;
    }
$number_un = array();
$number_bi = array();
$number_fo = array();
$number_ru = array();
$number_st = array();
$number_ti = array();
$number_ve = array();
$number_wa = array();

for($i = 0; $i <= count($rows1)-1; $i++) {
	
	if($rows1[$i][1] == 'STILL') {
		array_push($number_st,$rows1[$i][2]);
	}
	
	if($rows1[$i][1] == 'UNKNOWN') {
		array_push($number_un,$rows1[$i][2]);				
	}
	
	if($rows1[$i][1] == 'IN_VEHICLE') {
		array_push($number_ve,$rows1[$i][2]);
	}
	
	if($rows1[$i][1] == 'WALKING') {
		array_push($number_wa,$rows1[$i][2]);
	}

	if($rows1[$i][1] == 'TILTING') {		
		array_push($number_ti,$rows1[$i][2]);
	}
	
	if($rows1[$i][1] == 'ON_BICYCLE') {
		array_push($number_bi,$rows1[$i][2]);	
	}	
	
	if($rows1[$i][1] == 'RUNNING') {		
		array_push($number_ru,$rows1[$i][2]);
	}

	if($rows1[$i][1] == 'ON_FOOT') {		
		array_push($number_fo,$rows1[$i][2]);
	}
}

if(!empty($number_st)){
	$max_stH = max($number_st);
}
else {
	$max_stH = 0;
}
if(!empty($number_un)){
	$max_unH = max($number_un);
}
else {
	$max_unH = 0;
}
if(!empty($number_ve)){
	$max_veH = max($number_ve);
}
else {
	$max_veH = 0;
}
if(!empty($number_wa)){
	$max_waH = max($number_wa);
}
else {
	$max_waH = 0;
}
if(!empty($number_ti)){
	$max_tiH = max($number_ti);
}
else {
	$max_tiH = 0;
}
if(!empty($number_bi)){
	$max_biH = max($number_bi);
}
else {
	$max_biH = 0;
}
if(!empty($number_ru)){
	$max_ruH = max($number_ru);
}
else {
	$max_ruH = 0;
}
if(!empty($number_fo)){
	$max_foH = max($number_fo);
}
else {
	$max_foH = 0;
}
$ores_bi = array();
$ores_fo = array();
$ores_ru = array();
$ores_st = array();
$ores_ti = array();
$ores_un = array();
$ores_ve = array();
$ores_wa = array();
for($i = 0; $i <= count($rows1)-1; $i++) {
	
	if($max_stH != 0){
		if($rows1[$i][1] == 'STILL') {
			if($rows1[$i][2] == $max_stH) {
				array_push($ores_st,$rows1[$i][0]);	
			}
		}
	}
	
	if($max_unH != 0){
		if($rows1[$i][1] == 'UNKNOWN') {
			if($rows1[$i][2] == $max_unH) {
				array_push($ores_un,$rows1[$i][0]);	
			}
		}
	}
	
	if($max_veH != 0){
		if($rows1[$i][1] == 'IN_VEHICLE') {
			if($rows1[$i][2] == $max_veH) {
				array_push($ores_ve,$rows1[$i][0]);	
			}
		}
	}
	
	if($max_waH != 0){	
		if($rows1[$i][1] == 'WALKING') {
			if($rows1[$i][2] == $max_waH) {
				array_push($ores_wa,$rows1[$i][0]);	
			}
		}
	}

	if($max_tiH != 0){
		if($rows1[$i][1] == 'TILTING') {
			if($rows1[$i][2] == $max_tiH) {
				array_push($ores_ti,$rows1[$i][0]);	
			}
		}
	}

	if($max_biH != 0){	
		if($rows1[$i][1] == 'ON_BICYCLE') {
			if($rows1[$i][2] == $max_biH) {
				array_push($ores_bi,$rows1[$i][0]);	
			}
		}
	}
		
	if($max_waH != 0){
		if($rows1[$i][1] == 'RUNNING') {
			if($rows1[$i][2] == $max_ruH) {
				array_push($ores_ru,$rows1[$i][0]);	
			}
		}
	}
	
	if($max_foH != 0){
		if($rows1[$i][1] == 'ON_FOOT') {
			if($rows1[$i][2] == $max_foH) {
				array_push($ores_fo,$rows1[$i][0]);	
			}
		}
	}
}

$typeH = array();
$ores = array();
$num_ores = array();

if(!empty($ores_st)){
	
	array_push($typeH,'STILL');
	array_push($ores,implode(" /", $ores_st));
	array_push($num_ores,$max_stH);

}

if(!empty($ores_un)){
	
	array_push($typeH,'UNKNOWN');
	array_push($ores,implode(" /",$ores_un));
	array_push($num_ores,$max_unH);
}

if(!empty($ores_ve)){
	
	array_push($typeH,'IN_VEHICLE');
	array_push($ores,implode(" /", $ores_ve));
	array_push($num_ores,$max_veH);

}

if(!empty($ores_wa)){
	
	array_push($typeH,'WALKING');
	array_push($ores,implode(" /", $ores_wa));
	array_push($num_ores,$max_waH);

}

if(!empty($ores_ti)){
	
	array_push($typeH,'TILTING');
	array_push($ores,implode(" /", $ores_ti));
	array_push($num_ores,$max_tiH);

}

if(!empty($ores_bi)){
	
	array_push($typeH,'ON_BICYCLE');
	array_push($ores,implode(" /", $ores_bi));
	array_push($num_ores,$max_biH);

}

if(!empty($ores_ru)){
	
	array_push($typeH,'RUNNING');
	array_push($ores,implode(" /", $ores_ru));
	array_push($num_ores,$max_ruH);

}

if(!empty($ores_fo)){
	
	array_push($typeH,'ON_FOOT');
	array_push($ores,implode(" /", $ores_fo));
	array_push($num_ores,$max_foH);

}

# second query #
$rows2 = array();
foreach($res2 as $r2) {

    $temp2 = array();	
	  
    $temp2[] = (string) $r2['WeekDay']; 
	$temp2[] = (string) $r2['type']; 
    $temp2[] = (int) $r2['plithos2'];

    $rows2[] = $temp2;
    }

$numberD_un = array();
$numberD_bi = array();
$numberD_fo = array();
$numberD_ru = array();
$numberD_st = array();
$numberD_ti = array();
$numberD_ve = array();
$numberD_wa = array();

for($i = 0; $i <= count($rows2)-1; $i++) {
	
	if($rows2[$i][1] == 'STILL') {
		array_push($numberD_st,$rows2[$i][2]);		
	}
	
	if($rows2[$i][1] == 'UNKNOWN') {
		array_push($numberD_un,$rows2[$i][2]);		
	}
	
	if($rows2[$i][1] == 'IN_VEHICLE') {
		array_push($numberD_ve,$rows2[$i][2]);	
	}
	
	if($rows2[$i][1] == 'WALKING') {
		array_push($numberD_wa,$rows2[$i][2]);		
	}

	if($rows2[$i][1] == 'TILTING') {
		array_push($numberD_ti,$rows2[$i][2]);		
	}

	if($rows2[$i][1] == 'ON_BICYCLE') {
		array_push($numberD_bi,$rows2[$i][2]);
	}
	
	if($rows2[$i][1] == 'RUNNING') {
		array_push($numberD_ru,$rows2[$i][2]);
	}

	if($rows2[$i][1] == 'ON_FOOT') {
		array_push($numberD_fo,$rows2[$i][2]);
	}
}


if(!empty($numberD_st)){
	$max_st = max($numberD_st);
}
else{
	$max_st = 0;
}
if(!empty($numberD_un)){
	$max_un = max($numberD_un);
}
else {
	$max_un = 0;
}
if(!empty($numberD_ve)){
	$max_ve = max($numberD_ve);
}
else {
	$max_ve = 0;
}
if(!empty($numberD_wa)){
	$max_wa = max($numberD_wa);
}
else{
	$max_wa = 0;
}
if(!empty($numberD_ti)){
	$max_ti = max($numberD_ti);
}
else {
	$max_ti = 0;
}
if(!empty($numberD_bi)){
	$max_bi = max($numberD_bi);
}
else{
	$max_bi = 0;
}
if(!empty($numberD_ru)){
	$max_ru = max($numberD_ru);
}
else {
	$max_ru = 0;
}
if(!empty($numberD_fo)){
	$max_fo = max($numberD_fo);
}
else {
	$max_fo = 0;
}

$days_bi = array();
$days_fo = array();
$days_ru = array();
$days_st = array();
$days_ti = array();
$days_un = array();
$days_ve = array();
$days_wa = array();

for($i = 0; $i <= count($rows2)-1; $i++) {
	
	if($max_st != 0){
		if($rows2[$i][1] == 'STILL') {
			if($rows2[$i][2] == $max_st) {
				array_push($days_st,$rows2[$i][0]);	
			}
		}
	}
	
	if($max_un != 0){
		if($rows2[$i][1] == 'UNKNOWN') {
			if($rows2[$i][2] == $max_un) {
				array_push($days_un,$rows2[$i][0]);	
			}
		}
	}

	if($max_ve != 0){
		if($rows2[$i][1] == 'IN_VEHICLE') {
			if($rows2[$i][2] == $max_ve) {
				array_push($days_ve,$rows2[$i][0]);	
			}
		}
	}
	
	if($max_wa != 0){
		if($rows2[$i][1] == 'WALKING') {
			if($rows2[$i][2] == $max_wa) {
				array_push($days_wa,$rows2[$i][0]);	
			}
		}
	}
	
	if($max_ti != 0){
		if($rows2[$i][1] == 'TILTING') {
			if($rows2[$i][2] == $max_ti) {
				array_push($days_ti,$rows2[$i][0]);	
			}
		}
	}
	
	if($max_bi != 0){
		if($rows2[$i][1] == 'ON_BICYCLE') {
			if($rows2[$i][2] == $max_bi) {
				array_push($days_bi,$rows2[$i][0]);	
			}
		}
	}
	
	if($max_ru != 0){
		if($rows2[$i][1] == 'RUNNING') {
			if($rows2[$i][2] == $max_ru) {
				array_push($days_ru,$rows2[$i][0]);	
			}
		}
	}
	
	if($max_fo != 0){
		if($rows2[$i][1] == 'ON_FOOT') {
			if($rows2[$i][2] == $max_fo) {
				array_push($days_fo,$rows2[$i][0]);	
			}
		}
	}
}

$typeD = array();
$days = array();
$num_days = array();

if(!empty($days_st)){
	
	array_push($typeD,'STILL');
	array_push($days,implode(" /", $days_st));
	array_push($num_days,$max_st);

}

if(!empty($days_un)){
	
	array_push($typeD,'UNKNOWN');
	array_push($days,implode(" /",$days_un));
	array_push($num_days,$max_un);
}

if(!empty($days_ve)){
	
	array_push($typeD,'IN_VEHICLE');
	array_push($days,implode(" /", $days_ve));
	array_push($num_days,$max_ve);

}

if(!empty($days_wa)){
	
	array_push($typeD,'WALKING');
	array_push($days,implode(" /", $days_wa));
	array_push($num_days,$max_wa);

}

if(!empty($days_ti)){
	
	array_push($typeD,'TILTING');
	array_push($days,implode(" /", $days_ti));
	array_push($num_days,$max_ti);

}

if(!empty($days_bi)){
	
	array_push($typeD,'ON_BICYCLE');
	array_push($days,implode(" /", $days_bi));
	array_push($num_days,$max_bi);

}

if(!empty($days_ru)){
	
	array_push($typeD,'RUNNING');
	array_push($days,implode(" /", $days_ru));
	array_push($num_days,$max_ru);

}

if(!empty($days_fo)){
	
	array_push($typeD,'ON_FOOT');
	array_push($days,implode(" /", $days_fo));
	array_push($num_days,$max_fo);

}

# Heatmap Query #
$query3 = mysqli_query($conn, "SELECT latitude,longitude FROM egraf WHERE SUBSTRING(datetime,1,8) BETWEEN '$Start' AND '$End' AND type <> 'null' AND userid = '$_SESSION[userid]'");
$Lat = array();
$Lon = array();
$exists = array();
$markers = array();

  if(mysqli_num_rows($query3)>0){
	
	 array_push($exists, 1);
	 foreach($query3 as $row3) {
			 
	 array_push($Lat, $row3['latitude']);
	 array_push($Lon, $row3['longitude']);
	 
	}
  }
             
foreach($Lat as $i => $currentLat) {
	 
    $markers[] = array("lat" => $currentLat, "long" => $Lon[$i]);
 
}

if(empty($exists)) {
	 
	echo "<br><br><div class='container'><div class=\"alert alert-danger\">There are no records to be depicted regarding this period.</div></div>";
	
 }
 
else {

?>
<!DOCTYPE html>
<html>
<head>
<style>
.center-text {
    text-align: center;
  }
  
table {
  border-collapse: collapse;
  width: 90%;
}

td, th {
  text-align: center;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #FFEFD5;
}

table.center {
    margin-left:auto; 
    margin-right:auto;
  }
  
  .bold-font {
      font-weight: #2E8B57;
  }
  
  .large-font {
    font-size: 15px;
  }

  .italic-darkblue-font {
    font-style: italic;
    color: #2E8B57;
  }

  .blue-font {
    color: #2E8B57;
  }

  .gold-border {
    border: 3px solid #2E8B57;
  }

  .background {
    background-color: white;
  }
  
  .beige-background {
    background-color: #2E8B57;
  }
  


@media only screen and (max-width: 600px) {
  #map {
	  
	height:180px;
	width:250px;
	margin-right: auto; 
	border:solid black 1px;
  
  }
  
  #floating-panel {
  
	background-color: #fff;
    border: 1px solid #999;
    left: 70%;
	right:3%;
    position: absolute;
    top: 240px;
	z-index: 5;
	
  }
  
}

@media only screen and (min-width: 992px) {
  #map {
	  
	height:400px;
	width:1200px;
	margin-right: auto;
	margin-left: auto; 	
	border:solid black 1px;
  
  }
  
  #floating-panel {
        background-color: #fff;
        border: 1px solid #999;
        left: 35%;
        padding: 5px;
        position: absolute;
        top: 179px;
        z-index: 5;
  }
  
}

</style>
</head>
<body>
<br>
<br>
<div id="floating-panel">
      <button onclick="toggleHeatmap()">Toggle Heatmap</button>
      <button onclick="changeGradient()">Change gradient</button>
      <button onclick="changeRadius()">Change radius</button>
      <button onclick="changeOpacity()">Change opacity</button>
</div>

<div id="map" ></div>

<br>

<div align="center">
<div id="chart_div" ></div>
<br>
<div id="table_div"></div>
<br>
<div id="table_div1"></div>
<br>
<div id="table_div2"></div>
</div>
<br>
<br>
</body>
</html>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
      // Load Charts and the corechart package.  style="height:400px; width:1200px; margin-left: auto; margin-right: auto; border:solid black 1px;"
      google.charts.load('current', {'packages':['corechart']});
	  google.charts.load('current', {'packages':['table']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);
	  google.charts.setOnLoadCallback(drawTable);
	  google.charts.setOnLoadCallback(drawTable1);
	  google.charts.setOnLoadCallback(drawTable2);

	function round(value, decimals) {
		
		return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
		
	} 
	  
	  var cssClassNames = {
			'headerRow': 'italic-darkblue-font large-font bold-font',
			'tableRow': '',
			'oddTableRow': 'beige-background',
			'selectedTableRow': 'background large-font',
			'hoverTableRow': '',
			'headerCell': 'gold-border',
			'tableCell': 'center-text',
			'rowNumberCell': 'blue-font'
		};	

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      
	  function drawChart() {
		  
		  // Create our data table.
		var activ = <?php echo json_encode($activ); ?> ;
		var num = <?php echo json_encode($num); ?> ;

		var point = [['Type' , 'Number of Records']];
		for (var i = 0; i < activ.length; i++){
			
			point.push([ activ[i] , parseInt(num[i]) ]);

			};
			
		var data = google.visualization.arrayToDataTable(point);
		
        // Set chart options
        var options = {
			sliceVisibilityThreshold:0,
			width:440,
            height:300,
			title:"Το ποσοστό εγγραφών ανά είδος δραστηριότητας" 
									
			};

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
	  
	  function drawTable() {
		  
		var activ = <?php echo json_encode($activ); ?> ;
		var num = <?php echo json_encode($num); ?> ;
		var total = 0;
		for(var j = 0, len = num.length; j < len; j++) {

				total += parseInt(num[j]);
				
			};

		var point = [['Activity Type' , 'Number of Records', 'Percentage of Records (%)']];
		for (var i = 0; i < activ.length; i++){
			
			point.push([ activ[i] , parseInt(num[i]) , round((parseInt(num[i])/total)*100,2) ]);

			};

		var data = google.visualization.arrayToDataTable(point);
		var table = new google.visualization.Table(document.getElementById('table_div'));

        table.draw(data, {showRowNumber: false, width: '78%', height: '100%', 'cssClassNames': cssClassNames});
      }
	  
	  function drawTable1() {
        var type = <?php echo json_encode($typeH); ?> ;
		var ores = <?php echo json_encode($ores); ?> ;
		var num = <?php echo json_encode($num_ores); ?> ;
		var point = [['Activity Type' , 'Hour of the Day' , 'Number of Records']];
		for (var i = 0; i < type.length; i++){
			
			point.push([ type[i] , ores[i] , parseInt(num[i]) ]);

			};

		var data = google.visualization.arrayToDataTable(point);
		var table = new google.visualization.Table(document.getElementById('table_div1'));

        table.draw(data, {showRowNumber: false, width: '78%', height: '100%', 'cssClassNames': cssClassNames});
      }
	  
	  function drawTable2() {
        var typeD = <?php echo json_encode($typeD); ?> ;
		var days = <?php echo json_encode($days); ?> ;
		var numD = <?php echo json_encode($num_days); ?> ;
		var point = [['Activity Type' , 'Day of the Week' , 'Number of Records']];
		for (var i = 0; i < typeD.length; i++){
			
			point.push([ typeD[i] , days[i] , parseInt(numD[i]) ]);

			};

		var data = google.visualization.arrayToDataTable(point);
		var table = new google.visualization.Table(document.getElementById('table_div2'));

        table.draw(data, {showRowNumber: false, width: '78%', height: '100%', 'cssClassNames': cssClassNames});
      }

	  
// MAP //	  
	  var map;
function initializeMap() {
	var myMapOptions = {
			zoom: 11,
			center: new google.maps.LatLng(38.247376, 21.735990),
			mapTypeId: 'satellite'
	};
	map = new google.maps.Map(document.getElementById('map'),myMapOptions);
	var points = [];
	var markers = <?php echo json_encode($markers); ?>;
	for(var i in markers) {
        var marker = markers[i];
		points.push(new google.maps.LatLng(marker.lat, marker.long));
        };
	heatmap = new google.maps.visualization.HeatmapLayer({
		data: points,
        map: map
    });
}

function toggleHeatmap() {
        heatmap.setMap(heatmap.getMap() ? null : map);
      }

      function changeGradient() {
        var gradient = [
          'rgba(0, 255, 255, 0)',
          'rgba(0, 255, 255, 1)',
          'rgba(0, 191, 255, 1)',
          'rgba(0, 127, 255, 1)',
          'rgba(0, 63, 255, 1)',
          'rgba(0, 0, 255, 1)',
          'rgba(0, 0, 223, 1)',
          'rgba(0, 0, 191, 1)',
          'rgba(0, 0, 159, 1)',
          'rgba(0, 0, 127, 1)',
          'rgba(63, 0, 91, 1)',
          'rgba(127, 0, 63, 1)',
          'rgba(191, 0, 31, 1)',
          'rgba(255, 0, 0, 1)'
        ]
        heatmap.set('gradient', heatmap.get('gradient') ? null : gradient);
      }

      function changeRadius() {
        heatmap.set('radius', heatmap.get('radius') ? null : 20);
      }

      function changeOpacity() {
        heatmap.set('opacity', heatmap.get('opacity') ? null : 0.2);
      }
	
google.maps.event.addDomListener(window, 'load', initializeMap);	
	  
</script>	

<?php
mysqli_free_result($res);
mysqli_free_result($res1);
mysqli_close($dbhandle);

}

}
      
?> 