<?php 
include ("basic.php");
?>
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
  background-color: #9ACD32;
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
border-radius: 30px;
}

.w3-button {
		background-color: #B22222;
		color: white;
		padding: 7px 7px;
		text-align: center;
		display: inline-block;
		margin-left: 1%;
		text-decoration: none;
		border-radius: 8px;
		
	}
</style>

	<div class='container'>
    <form method="post">
        <div class='col-md-3 col-md-push-3'>
					<br>
					<br>
					<label>From:</label>
                    <input type='text' size="18"  id='picker1' name= 'picker1' value = "<?php if (isset($_POST['picker1'])){ echo  $_POST['picker1']; } ?>" required />
        </div>
        <div class='col-md-4 col-md-push-2'>
					<br>
					<br>
					<label>To:</label>
                    <input type='text' size="18" id='picker2' name = 'picker2' value = "<?php if (isset($_POST['picker2'])){ echo  $_POST['picker2']; } ?>" required />
        </div>
		<div class='col-md-2'>
		<select required name="myselectbox[]" multiple>
				<option  name = "IN_VEHICLE" value="IN_VEHICLE">IN_VEHICLE</option>
				<option  name = "ON_BICYCLE" value="ON_BICYCLE">ON_BICYCLE</option>
				<option  name = "ON_FOOT" value="ON_FOOT">ON_FOOT</option>
				<option  name = "RUNNING" value="RUNNING">RUNNING</option>
				<option  name = "STILL" value="STILL">STILL</option>
				<option  name = "TILTING" value="TILTING">TILTING</option>
				<option  name = "UNKNOWN" value="UNKNOWN">UNKNOWN</option>
				<option  name = "WALKING" value="WALKING">WALKING</option>
		</select>
		</div>
		<br>
		<input type="submit" name="search" class="button button1" value="SEARCH" /> 
		<button id="deletion" onClick="deleteme()" class="w3-button">Delete Data</button>
    </div>
	</form>
	<br>

    <script type="text/javascript">
        $(function () {
            $('#picker1').datetimepicker({
			format: 'YYYY-MM-DD HH:mm',
			maxDate: new Date(),
			useCurrent: false
			});
            $('#picker2').datetimepicker({
			format: 'YYYY-MM-DD HH:mm',
			maxDate: new Date(),
			useCurrent: false
			});
            $("#picker1").on("dp.change", function (e) {
                $('#picker2').data("DateTimePicker").minDate(e.date);
            });
            $("#picker2").on("dp.change", function (e) {
                $('#picker1').data("DateTimePicker").maxDate(e.date);
            });		
        });
		
		function deleteme() {
			if(confirm("Are you sure you want to permanently delete the database?")){
				window.location.href='DeleteData.php';
				return true;
			}
		} 
</script>
   
<?php

$UserId = array();
$Dt = array();
$Head = array();
$Verti = array();
$Velo = array();
$Alti = array();
$Lat = array();
$Lon = array();
$Acc = array();
$Tp = array();
$Con = array();
$markers = array();
$for_exp = array();
$exists = array();

if (isset($_POST['search'])) {
  
  $from = $_POST['picker1'];
  $to = $_POST['picker2'];

  foreach ($_POST['myselectbox'] as $activity){

  $query = mysqli_query($conn, "SELECT userid,datetime,heading,verticalAccuracy,velocity,altitude,latitude,longitude,accuracy,type,confidence FROM `egraf` WHERE SUBSTRING(datetime, 1, 16) BETWEEN '$from' AND '$to' AND `type` LIKE '%$activity%'");
     	 
  if(mysqli_num_rows($query)>0){
	  
	 array_push($exists, 1); //elegxos	 
	 foreach($query as $row) {
		 
	 array_push($UserId, $row['userid']);
	 array_push($Dt, $row['datetime']);
	 array_push($Head, $row['heading']);
	 array_push($Verti, $row['verticalAccuracy']);
	 array_push($Velo, $row['velocity']);
	 array_push($Alti, $row['altitude']);
	 array_push($Lat, $row['latitude']);  
	 array_push($Lon, $row['longitude']);
	 array_push($Acc, $row['accuracy']);
	 array_push($Tp, $row['type']); 
	 array_push($Con, $row['confidence']);  

	}	
  }
}

foreach($Lat as $i => $currentLat) {
	 
    $markers[] = array("lat" => $currentLat, "long" => $Lon[$i]); //heatmap
    $for_exp[] = array("heading" =>$Head[$i], "activity.type" =>$Tp[$i], "activity.confidence" =>$Con[$i], "activity.date" =>$Dt[$i], "verticalAccuracy" =>$Verti[$i], "velocity" =>$Velo[$i], "accuracy" =>$Acc[$i], "latitude" => $currentLat, "longitude" => $Lon[$i], "altitude" => $Alti[$i], "userid" => $UserId[$i]);
 
 }

 if(empty($exists)) {
	 
	echo "<div class='container'><div class=\"alert alert-danger\">There are no records to be depicted regarding this period and activity type(s).</div></div>";
	
 }
 
 else {
?>
	 

<!DOCTYPE html>
<html>
<body>
	<head>
    <meta charset="utf-8">
    <style>
      #floating-panel {
        background-color: #fff;
        border: 1px solid #999;
        left: 35%;
        padding: 5px;
        position: absolute;
        top: 165px;
        z-index: 5;
      }
    </style>
	</head>

	<div id="floating-panel">
      <button onclick="toggleHeatmap()">Toggle Heatmap</button>
      <button onclick="changeGradient()">Change gradient</button>
      <button onclick="changeRadius()">Change radius</button>
      <button onclick="changeOpacity()">Change opacity</button>
    </div>

	<div id="map" style="height:400px; width:1200px; margin-left: auto; margin-right: auto; border:solid black 1px;"></div>

	<br>
    
	<div style="text-align:center">
	<input type="button" onclick="exportToCsvFile()" class="btn btn-info" value="EXPORT as CSV" /> 
	<input type="button" onclick="exportToXmlFile()" class="btn btn-info" value="EXPORT as XML" /> 
	<input type="button" onclick="exportToJsonFile()" class="btn btn-info" value="EXPORT as JSON" /> 
	</div>
	<br>
	<br>
</body>
</html>

<script>
var map;
function initializeMap() {
	var myMapOptions = {
			zoom: 11,
			center: new google.maps.LatLng(38.247645, 21.735304),
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

// EXPORT TO JSON	  
function exportToJsonFile() {

   	var jsonData = <?php echo json_encode($for_exp); ?>;

    let dataStr = JSON.stringify(jsonData, null, '\t');
    let dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);

    let exportFileDefaultName = 'data.json';

    let linkElement = document.createElement('a');
    linkElement.setAttribute('href', dataUri);
    linkElement.setAttribute('download', exportFileDefaultName);
    linkElement.click();
}

// EXPORT TO CSV
function exportToCsvFile() {
	
	var jsonData = <?php echo json_encode($for_exp); ?>;

    if(jsonData.length == 0) {
        return '';
    }

    let keys = Object.keys(jsonData[0]);

    let columnDelimiter = ',';
    let lineDelimiter = '\n';

    let csvColumnHeader = keys.join(columnDelimiter);
    let csvStr = csvColumnHeader + lineDelimiter;

    jsonData.forEach(item => {
	keys.forEach((key, index) => {
		csvStr += item[key];
		if( index < keys.length-1 ) {
			csvStr += columnDelimiter;
		}
	});
        csvStr += lineDelimiter;
    });
	
	csvStr = encodeURIComponent(csvStr);
	
	let dataUri = 'data:text/csv;charset=utf-8,'+ csvStr;

    let exportFileDefaultName = 'data.csv';

    let linkElement = document.createElement('a');
    linkElement.setAttribute('href', dataUri);
    linkElement.setAttribute('download', exportFileDefaultName);
    linkElement.click();
}

// EXPORT TO XML
function formatXml(xml) {
    var formatted = '';
    var reg = /(>)(<)(\/*)/g;
    xml = xml.replace(reg, '$1\r\n$2$3');
    var pad = 0;
    jQuery.each(xml.split('\r\n'), function(index, node) {
        var indent = 0;
        if (node.match( /.+<\/\w[^>]*>$/ )) {
            indent = 0;
        } else if (node.match( /^<\/\w/ )) {
            if (pad != 0) {
                pad -= 1;
            }
        } else if (node.match( /^<\w[^>]*[^\/]>.*$/ )) {
            indent = 1;
        } else {
            indent = 0;
        }

        var padding = '';
        for (var i = 0; i < pad; i++) {
            padding += '  ';
        }

        formatted += padding + node + '\r\n';
        pad += indent;
    });

    return formatted;
}

function exportToXmlFile() {
	
	var jsonData = <?php echo json_encode($for_exp); ?>;

	let jsonString = JSON.stringify(jsonData, null, '\t');
	let fileName = 'data.xml';
	let xmlStr = new X2JS().json2xml_str(JSON.parse(jsonString, null, '\t'));
	let fin = formatXml(xmlStr);
	let a = document.createElement('a');
	a.download = fileName;
	a.href = URL.createObjectURL(new File([fin], fileName, {type: 'text/xml'}));
	a.click();
}

</script>
	<?php

 }
}
 
 ?>