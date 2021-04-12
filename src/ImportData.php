 <?php
 
 include ("basic.php");
  
 ?>

<!DOCTYPE html>  
<html>  
 <head>  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script type="text/javascript"
        src="http://maps.google.com/maps/api/js?key=AIzaSyDB8Hzdr9qGqPyzhjLJOmXypyPlpQO0fIk&libraries=drawing"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
 </head> 
 <body> 
<style>
@media only screen and (max-width: 600px) {
  #map {
	  
	height:300px;
	width:360px;
	margin-right: auto; 
	border:solid black 1px;
  
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
    
}

</style>
 
<div  class="container" id="output"></div>	
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <meta charset="utf-8">
  <form method="post" enctype="multipart/form-data" >
   <div align="center">  
    <h4>Select a Json file:</h4>
	<input id = "file" type="file" name="file" />
	<h4>Select one or more areas from which you may not want to upload data:</h4>
	<div id="map"></div>
    <br>
	<button type="button" id="import" class="btn btn-primary mt-5"> IMPORT </button>
	<br>
	<br>
   </div>
  </form>
 </body>  
</html>

<script type="text/javascript">

   //	<input type="button" id="import"  class="btn btn-info" value="IMPORT" /> 
					
			var drawingManager;
            var selectedShape;
			var coordinates=[];
	
            function clearSelection () {
                if (selectedShape) {
                    selectedShape.setEditable(false);
                    selectedShape = null;
                }
            }

            function setSelection (shape) {
                clearSelection();
				
				var bounds = shape.getBounds(); // perno ta lat kai lng
				var ne = bounds.getNorthEast(); // PD
				var sw = bounds.getSouthWest(); //KA
				
				var thi = JSON.stringify(ne);
				var co = JSON.parse(thi);
				var PD_lat = co.lat;
				var PD_lon = co.lng;
				var thi1 = JSON.stringify(sw);
				var co1 = JSON.parse(thi1);
				var KA_lat = co1.lat;
				var KA_lon = co1.lng;

                selectedShape = shape;
                shape.setEditable(true);
				return [PD_lat,PD_lon,KA_lat,KA_lon];
				
            }

            function initialize () {
                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 13,
                    center: new google.maps.LatLng(38.247645, 21.735304),
                    disableDefaultUI: true,
                    zoomControl: true
                });

                // Creates a drawing manager attached to the map that allows the user to draw
                // markers, lines, and shapes.
                drawingManager = new google.maps.drawing.DrawingManager({
                    drawingMode: google.maps.drawing.OverlayType.RECTANGLE,
					drawingControl: true,
					drawingControlOptions: {
						position: google.maps.ControlPosition.TOP_CENTER,
						drawingModes: ['rectangle']
					},
                    rectangleOptions: {
						strokeWeight: 1,
                        editable: true,
                        draggable: true
                    }
                });

                google.maps.event.addListener(drawingManager, 'overlaycomplete', function (e) {
                        // Switch back to non-drawing mode after drawing a shape.
                        drawingManager.setDrawingMode(null);

                        // Add an event listener that selects the newly-drawn shape when the user
                        // mouses down on it.
                        var newShape = e.overlay;
						var values = setSelection(newShape);
						
						var PD_lat = values[0];
						var PD_lon = values[1];
						var KA_lat = values[2];
						var KA_lon = values[3];
						coordinates.push([PD_lat,PD_lon,KA_lat,KA_lon]);												
                });
								
				// Clear the current selection when the drawing mode is changed, or when the
                // map is clicked.
                google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
                google.maps.event.addListener(map, 'click', clearSelection);

				drawingManager.setMap(map);

            }
            google.maps.event.addDomListener(window, 'load', initialize);			
						
			document.getElementById('import').onclick = function() {

				var files = document.getElementById('file').files;
				var fileInput = document.querySelector("#file");
				var files = fileInput.files;

				// cache files.length 
				var fl = files.length;
				if(fl == 0){
					alert("Please insert a Json file.");
				}
				else{
				var i = 0;

				while (i < fl) {
				// localize file var in the loop
					var file = files[i];
					var name = file.name;
					i++;
				}    
				
				var res = name.split(".");
				
				if(res[1] != "json"){
				
					alert("Please make sure the file is on a JSON format.");
					
				}
				
				else{
				
				$('#import').html('<i class="fa fa-circle-o-notch fa-spin"></i>  Loading');
				var fr = new FileReader();
				fr.onload = function(e) { 
					var file = e.target.result; // string
					$.ajax({
						url: "UploadData.php",
						data: {'file' : file, 'coo' : JSON.stringify(coordinates)},
						type: 'post',
						dataType: 'json',
						success: function(value){
							setTimeout(function(){location.href="ImportData.php"} , 2000);
							if(value.msg == 'true'){
								
								msg = "Your file has been successfully uploaded.";
								$("#output").html("<div class='alert alert-success'>"+msg+"</div>");
									
							}
							else if(value.msg == 'empty') {
								
								msg = "None of your records have been uploaded due to restrictions.";
								$("#output").html("<div class='alert alert-warning'>"+msg+"</div>");
								
							}
							else{
								
								msg = "There was an error uploading your file.";
								$("#output").html("<div class='alert alert-danger'>"+msg+"</div>");
								
							}
						
						}
					});
					  
				}
				fr.readAsText(files.item(0));
			};
			}
			}
				
        </script>
