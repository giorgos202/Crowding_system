<?php 
include ("basic.php");
$dbhandle = new mysqli('localhost','root','','test1');
$query = "SELECT type,count(*) as plithos FROM egraf WHERE type <> 'null' GROUP BY type";
$query1 = "SELECT MONTHNAME(datetime) as month,count(*) as plithos1 FROM egraf GROUP BY month";
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

$count = mysqli_num_rows($res5);

if ($count == "0") {
    
	echo "<div class='container'><div class=\"alert alert-danger\">There are no records to be depicted.</div></div>";
  }

else {
	
$tp = array();
$num  = array();	
foreach($res as $r) {
	
	array_push($tp,$r['type']);
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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

      // Load Charts and the corechart package.
      google.charts.load('current', {'packages':['corechart']});
	  

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart1);
      google.charts.setOnLoadCallback(drawChart2);
	  google.charts.setOnLoadCallback(drawChart3);
	  google.charts.setOnLoadCallback(drawChart4);
	  google.charts.setOnLoadCallback(drawChart5);
	  google.charts.setOnLoadCallback(drawChart6);
      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      
	  function drawChart1() {

		var tp = <?php echo json_encode($tp); ?> ;
		var num = <?php echo json_encode($num); ?> ;

		var point = [['Type' , 'Number of Records']];
		for (var i = 0; i < tp.length; i++){
			
			point.push([ tp[i] , parseInt(num[i]) ]);

			};
			
		var data = google.visualization.arrayToDataTable(point);
		
        // Set chart options
        var options = {
			sliceVisibilityThreshold:0,
			title: 'Το ποσοστό εγγραφών ανά είδος δραστηριότητας',
			width:600,
            height:500
									
			};

        var chart = new google.visualization.PieChart(document.getElementById('chart_div1'));
        chart.draw(data, options);
      }
	        
	  function drawChart2() {

		var mon = <?php echo json_encode($mon); ?> ;
		var num1 = <?php echo json_encode($num1); ?> ;

		var point = [['Month' , 'Number of Records']];
		for (var i = 0; i < mon.length; i++){
			
			point.push([ mon[i] , parseInt(num1[i]) ]);

			};

		var data = google.visualization.arrayToDataTable(point);

        var options = {
          title: 'Η κατανομή του πλήθους των εγγραφών ανά μήνα',
          legend: { position: 'none' },
		  colors: ['#e7711c']
        };

        var chart = new google.visualization.Histogram(document.getElementById('chart_div2'));
        chart.draw(data, options);
      }

	  
	  function drawChart3() {

        var day = <?php echo json_encode($d); ?> ;
		var num2 = <?php echo json_encode($num2); ?> ;

		var point = [['Day of the Week' , 'Number of Records']];
		for (var i = 0; i < day.length; i++){
			
			point.push([ day[i] , parseInt(num2[i]) ]);

			};

		var data = google.visualization.arrayToDataTable(point);
		
		
        var options = {
          title: 'Η κατανομή του πλήθους των εγγραφών ανά ημέρα της εβδομάδας',
          legend: { position: 'none' },
		  colors: ['#e7711c']
        };

        var chart = new google.visualization.Histogram(document.getElementById('chart_div3'));
        chart.draw(data, options);
      }
	  
	  function drawChart4() {

        // Create our data table.
        var time = <?php echo json_encode($t); ?> ;
		var num3 = <?php echo json_encode($num3); ?> ;
		
		var point = [['Hour of the Day' , 'Number of Records']];
		for (var i = 0; i < time.length; i++){
			
			point.push([ time[i] , parseInt(num3[i]) ]);

			};

		var data = google.visualization.arrayToDataTable(point);
		
		var options = {
          title: 'Η κατανομή του πλήθους των εγγραφών ανά ώρα',
          legend: { position: 'none' },
  		  colors: ['green']

        };

        var chart = new google.visualization.Histogram(document.getElementById('chart_div4'));
        chart.draw(data, options);
      }
	  
      function drawChart5() {

        var year = <?php echo json_encode($y); ?> ;
		var num4 = <?php echo json_encode($num4); ?> ;
		
		var point = [['Year' , 'Number of Records']];
		for (var i = 0; i < year.length; i++){
			
			point.push([ year[i] , parseInt(num4[i]) ]);

			};

		var data = google.visualization.arrayToDataTable(point);
				
		var options = {
          title: 'Η κατανομή του πλήθους των εγγραφών ανά έτος',
          legend: { position: 'none' },
		  colors: ['green']
        };

        var chart = new google.visualization.Histogram(document.getElementById('chart_div5'));
        chart.draw(data, options);
      }
	  
	  function drawChart6() {

        var user = <?php echo json_encode($u); ?> ;
		var num5 = <?php echo json_encode($num5); ?> ;

		var point = [['User' , 'Number of Records']];
		for (var i = 0; i < user.length; i++){
			
			point.push([ user[i] , parseInt(num5[i]) ]);

			};

		var data = google.visualization.arrayToDataTable(point);
		
		var options = {
          title: 'Η κατανομή του πλήθους των εγγραφών ανά χρήστη',
          legend: { position: 'none' }
        };

        var chart = new google.visualization.Histogram(document.getElementById('chart_div6'));
        chart.draw(data, options);
      }
	  
  </script>
  </head>
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
	<h3>Graph Depiction</h3>
	  </div>
	  	<button onClick="change()" class="button">Tables</button>

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
		window.location.href='TableChart.php';
 } 
 </script>
  <body>
	 <table class="container">
    <tr>
        <td><div id="chart_div1" style="width: 600px; height: 500px;"></div></td>
        <td><div id="chart_div6" style="width: 600px; height: 500px;"></div></td>
    </tr>
	<tr>
        <td><div id="chart_div2" style="width: 600px; height: 500px;"></div></td>
        <td><div id="chart_div3" style="width: 600px; height: 500px;"></div></td>
    </tr>
	<tr>
		<td><div id="chart_div4" style="width: 600px; height: 500px;"></div></td>
        <td><div id="chart_div5" style="width: 600px; height: 500px;"></div></td>
    </tr>
	</table>
 </body>
</html>

<?php

}

?>