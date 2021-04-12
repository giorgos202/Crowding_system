<?php 
include ("basic.php");
$dbhandle = new mysqli('localhost','root','','test1');

$query = "SELECT type,count(*) as plithos FROM egraf WHERE type <> 'null' GROUP BY type";
$query1 = "SELECT MONTHNAME(datetime)as month,count(*) as plithos1 FROM egraf GROUP BY month";
$query2 = "SELECT DAYNAME(datetime) as day,count(*) as plithos2 FROM egraf GROUP BY day";
$query3 = "SELECT SUBSTRING(datetime, 11, 3) as time,count(*) as plithos3 FROM egraf GROUP BY time";
$query4 = "SELECT YEAR(datetime) as year,count(*) as plithos4 FROM egraf GROUP BY year";
$query5 = "SELECT userid,count(userid) as plithos5 FROM egraf GROUP BY userid";
$res = $dbhandle->query($query);
$res1 = $dbhandle->query($query1);
$res2 = $dbhandle->query($query2); 
$res3 = $dbhandle->query($query3);
$res4 = $dbhandle->query($query4);
$res5 = $dbhandle->query($query5);
 
$activ = array();
$num  = array();
foreach($res as $r) {

      array_push($activ,$r['type']);
	  array_push($num,$r['plithos']);
	  
	  }

$mon = array();
$num1  = array();	
foreach($res1 as $r1) {
	
	array_push($mon,$r1['month']);
	array_push($num1,$r1['plithos1']);
	
	}
	
$d = array();
$num2 = array();
foreach($res2 as $r2) {

	array_push($d,$r2['day']);
	array_push($num2,$r2['plithos2']);

	}

$t = array();
$num3 = array();
$minutes = '00';
foreach($res3 as $r3) {
	
	$arr = [$r3['time'],$minutes];
    $arri = implode(':',$arr);	
	array_push($t,$arri);
	array_push($num3,$r3['plithos3']);

    }
	
$y = array();
$num4 = array();
foreach($res4 as $r4) {

    array_push($y,$r4['year']);
	array_push($num4,$r4['plithos4']);

    }

$u = array();
$num5 = array();
$tt = 1;
$user = 'User';
foreach($res5 as $r5) {

	$arr1 = [$user,$tt];
    $arri1 = implode(' ',$arr1);
	array_push($u,$arri1);
	array_push($num5,$r5['plithos5']);
	$tt = $tt + 1;

    }

mysqli_free_result($res);
mysqli_free_result($res1);
mysqli_free_result($res2);
mysqli_free_result($res3);
mysqli_free_result($res4);
mysqli_free_result($res5);
mysqli_close($dbhandle);

?>
<style>
  .bold-font {
    font-weight: bold;
  }

  .left-text {
    text-align: left;
  }

  .large-font {
    font-size: 15px;
  }

  .italic-darkblue-font {
    font-style: italic;
    color: darkblue;
  }

  .blue-font {
    color: darkblue;
  }

  .gold-border {
    border: 3px solid gold;
  }

  .background {
    background-color: white;
  }
  
  .beige-background {
    background-color: beige;
  }

</style>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
	
      google.charts.load('current', {'packages':['table']});
	  
	  google.charts.setOnLoadCallback(drawTable);
	  google.charts.setOnLoadCallback(drawTable1);
	  google.charts.setOnLoadCallback(drawTable2);
	  google.charts.setOnLoadCallback(drawTable3);
	  google.charts.setOnLoadCallback(drawTable4);
	  google.charts.setOnLoadCallback(drawTable5);
	  
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
			'tableCell': 'left-text',
			'rowNumberCell': 'blue-font'
		};		  
	  
	  function drawTable() {
		  
		var activ = <?php echo json_encode($activ); ?> ;
		var num = <?php echo json_encode($num); ?> ;
		var paronomastis = 0;
		for(var j = 0, len = num.length; j < len; j++) {

				paronomastis += parseInt(num[j]);
				
		};

		var point = [['Activity Type' , 'Number of Records', 'Percentage of Records (%)']];
		for (var i = 0; i < activ.length; i++){
			
			point.push([ activ[i] , parseInt(num[i]) , round((parseInt(num[i])/paronomastis)*100,2) ]);

			};

		var data = google.visualization.arrayToDataTable(point);
		var table = new google.visualization.Table(document.getElementById('table_div1'));

        table.draw(data, {showRowNumber: true, width: '70%', height: '100%', 'cssClassNames': cssClassNames});
      }
	  
	  function drawTable1() {
				
		var mon = <?php echo json_encode($mon); ?> ;
		var num1 = <?php echo json_encode($num1); ?> ;
		var total1 = 0;
		for(var j = 0, len = num1.length; j < len; j++) {

				total1 += parseInt(num1[j]);
				
			};

		var point = [['Month' , 'Number of Records', 'Percentage of Records (%)']];
		for (var i = 0; i < mon.length; i++){
			
			point.push([ mon[i] , parseInt(num1[i]) , round((parseInt(num1[i])/total1)*100,2) ]);

			};

		var data = google.visualization.arrayToDataTable(point);
		var table = new google.visualization.Table(document.getElementById('table_div2'));

        table.draw(data, {showRowNumber: true, width: '70%', height: '100%', 'cssClassNames': cssClassNames});
      }
	  
	  function drawTable2() {
		
		var day = <?php echo json_encode($d); ?> ;
		var num2 = <?php echo json_encode($num2); ?> ;
		var total2 = 0;
		for(var j = 0, len = num2.length; j < len; j++) {

				total2 += parseInt(num2[j]);
				
			};

		var point = [['Day of the Week' , 'Number of Records', 'Percentage of Records (%)']];
		for (var i = 0; i < day.length; i++){
			
			point.push([ day[i] , parseInt(num2[i]) , round((parseInt(num2[i])/total2)*100,2) ]);

			};

		var data = google.visualization.arrayToDataTable(point);
		var table = new google.visualization.Table(document.getElementById('table_div3'));

        table.draw(data, {showRowNumber: true, width: '70%', height: '100%', 'cssClassNames': cssClassNames});
      }
	  
	  function drawTable3() {
		
		var time = <?php echo json_encode($t); ?> ;
		var num3 = <?php echo json_encode($num3); ?> ;
		var total3 = 0;
		for(var j = 0, len = num3.length; j < len; j++) {

				total3 += parseInt(num3[j]);
				
			};

		var point = [['Hour of the Day' , 'Number of Records', 'Percentage of Records (%)']];
		for (var i = 0; i < time.length; i++){
			
			point.push([ time[i] , parseInt(num3[i]) , round((parseInt(num3[i])/total3)*100,2) ]);

			};

		var data = google.visualization.arrayToDataTable(point);
		var table = new google.visualization.Table(document.getElementById('table_div4'));

        table.draw(data, {showRowNumber: true, width: '70%', height: '100%', 'cssClassNames': cssClassNames});
      }
	  
	  function drawTable4() {
		
		var year = <?php echo json_encode($y); ?> ;
		var num4 = <?php echo json_encode($num4); ?> ;
		var total4 = 0;
		for(var j = 0, len = num4.length; j < len; j++) {

				total4 += parseInt(num4[j]);
				
			};

		var point = [['Year' , 'Number of Records', 'Percentage of Records (%)']];
		for (var i = 0; i < year.length; i++){
			
			point.push([ year[i] , parseInt(num4[i]) , round((parseInt(num4[i])/total4)*100,2) ]);

			};

		var data = google.visualization.arrayToDataTable(point);
		var table = new google.visualization.Table(document.getElementById('table_div5'));

        table.draw(data, {showRowNumber: true, width: '70%', height: '100%', 'cssClassNames': cssClassNames});
      }
	  
	  function drawTable5() {
		  
		var user = <?php echo json_encode($u); ?> ;
		var num5 = <?php echo json_encode($num5); ?> ;
		var total5 = 0;
		for(var j = 0, len = num5.length; j < len; j++) {

				total5 += parseInt(num5[j]);
				
			};

		var point = [['User' , 'Number of Records', 'Percentage of Records (%)']];
		for (var i = 0; i < user.length; i++){
			
			point.push([ user[i] , parseInt(num5[i]) , round((parseInt(num5[i])/total5)*100,2) ]);

			};

		var data = google.visualization.arrayToDataTable(point);
		var table = new google.visualization.Table(document.getElementById('table_div6'));

        table.draw(data, {showRowNumber: true, width: '70%', height: '100%', 'cssClassNames': cssClassNames});
      }
    </script>
	
  <html>
  <head>
  <style>
      #switch {
		color: black;
        left: 43%;
        padding: 5px;
        position: absolute;
        top: 60px;
        z-index: 5;
      }
	
	.button {
		background-color: #008080;
		color: white;
		padding: 14px 25px;
		text-align: center;
		display: inline-block;
		margin-left: 70%;
		text-decoration: none;
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
	<div id="switch">
	<h3>Table Depiction</h3>
	  </div>
	  	<button onClick="change()" class="button">Graphs</a>
    <body>
		<button id="deletion" onClick="deleteme()" class="w3-button">Delete Data</button>
		<br>
		<br>
		</div>
		</div>
	</body>
</html>
<script language="javascript">
 function deleteme()
 {
 if(confirm("Are you sure you want to permanently delete the database?")){
 window.location.href='DeleteData.php';
 return true;
 }
 } 
 
  function change()
 {
 window.location.href='Graphs.php';
 }
 </script>
  <body>
    <div style= "margin-left:25%;">
	<div id="table_div1"></div>
	<br>
	<div id="table_div6"></div>
	<br>
	<div id="table_div2"></div>
	<br>
	<div id="table_div3"></div>
	<br>
	<div id="table_div4"></div>
	<br>
	<div id="table_div5"></div>
	<br>
	<br>
	</div>      
  </body>
</html>