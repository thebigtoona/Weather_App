<?php

// printing get array for testing purposes 
// print_r($_GET);

// pre-defined the error and weather variables to try to avoid warnings/exceptions
$city = '';
$weatherArray = [];

// if a get var for 'city' is set and also not equal to an empty string proceed 
if(isset($_GET['city']) AND $_GET['city'] != '') {  
	
	// var holding url and api-key to the weather api @ symbol is to suppress warnings/errors	
	$urlContents = @file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=".urlencode($_GET['city'])."&type=like&units=imperial&appid=08888e60bdc75da24651905faef2d156");
	
	// if ($urlContents === FALSE) { echo "That City Could Not Be Found. Please Try Again"; }
	
	// array created from api json using json_decode and the url contents 
	$weatherArray = json_decode($urlContents, true);

	
	// printing weatherArray for testing purposes 
	// print_r($weatherArray);
	
	// checks that both the code for input is 200 (valid entry code) and that the name key of the array matches the city, ignoring case with strcasecmp func
	if ($weatherArray['cod'] == 200  && strcasecmp($weatherArray['name'], $_GET['city']) == 0) {
		
		// location of the city listed 
		$location = $weatherArray['name'].", ".$weatherArray['sys']['country'];
		
		// shows the basic weather info for the city 
		$currentWeather = "Current Weather Description: '".$weatherArray['weather'][0]['main']."'. ";
		
		// converts the temp to C from the weather array  
		$tempInFarenheit = intval($weatherArray['main']['temp']);
		// $tempInFarenheit = intval((($weatherArray['main']['temp'] - 273) * 1.8) + 32);
		
		
		// longitutde and latitude to pass to the js for google maps api section 
		$lon = $weatherArray['coord']['lon'];
		$lat = $weatherArray['coord']['lat'];
	
		// echoing longitude and lat var as test 
		// echo $lon;
		// echo $lat; 
		
	}
			
} 


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Weather App</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
	<!-- <link rel="stylesheet" type="text/css" href="./styles/main.css"> -->
	<style>
		#map { height: 180px; width: 100%; }
		body {  background: no-repeat center center fixed; 
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: cover; 
			
			 }
		.formColor { background: rgba(255, 255, 255, 0.5); }
	
	</style>
  
  </head>

  <body>
	  
	  
	<div class="formColor">  
	
	<div class="row">
	
	<div class="col-md mt-3">
		<div class="container">
			
				<?php 
				
				// if get city var is set and not equal to an empty string 
				if (isset($_GET['city']) AND $_GET['city'] != '') {	
					// this is where the weather information displays in the card
					if ($weatherArray['cod'] == 200  && strcasecmp($weatherArray['name'], $_GET['city']) == 0) {
						
						// start the card
						echo '<div class="card formColor"> <div id="map"></div> <div class="card-block"><p class="card-text">';	
						//start unordered list
						echo '<ul>';
						echo '<li>Location: '.$location.'</li>';
						echo '<li>'.$currentWeather.'</li>';
						echo '<li>Temperature: '.$tempInFarenheit.'&deg;F</li>';
						echo '</ul>';
						// end unordered list 
						echo '</p></div></div>';
						
					} 
					// if weather array code is not equal to 200 or if the weatherarray name and get city do not match
					if ($weatherArray['cod'] != 200 AND strcasecmp($weatherArray['name'], $_GET['city']) != 0) { 
						
						echo "Please Try Again. City Not Found"; 
					
					} 
					
					
				} else { }
				
				
				
				?>	
			
		</div>
	</div><!-- End Col -->
	
	<div class="col-md text-center mt-2 ">
		<div class="row">
			<div class="col">
				<div class="container mt-3">
					<h1>What's The Weather?</h1>
				</div>
			</div> <!-- End Nested Col -->
		</div> <!-- End Row -->
		<div class="row">
			<div class="col">
				<div class="container mt-1">
					<form method="get">
						<fieldset class="form-group">
						<label for='city' class="mb-3" ><strong>Enter the name of a city:</strong></label>
						<input type="text" class="form-control mb-3" name='city' id='city' placeholder="e.g. London" value="<?php if (isset($_GET['city'])) { echo $_GET['city']; } ?>">
						</fieldset>
						<button type="submit" class="btn btn-primary mb-3">Submit</button>
					</form> <!-- End Form -->
				</div> <!-- End Container -->
			</div> <!-- End Nested Col -->
		</div> <!-- End Row -->
		<div class="row">
			<div class="col">
				<div class="container">
					<div id="weather">
						
					</div>
				</div> <!-- End Container -->
			</div> <!-- End Nested Col -->
		</div> <!-- End Row -->
	</div> <!-- End Col -->
	
	</div> <!-- End Row -->
    
    </div> <!-- End formColor -->
    
    
    
    
    <script>
      function initMap() {
		  
		// var for location coordinates.  $lat is latitude pulled from weather api, $lon is longitude pulled from weather api  
        var uluru = { lat: <?php print $lat; ?>, lng: <?php print $lon; ?> };
        
        // creates a new map object centered at the location specified above by the var uluru
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 4,
          center: uluru
        });
        
        // this creates a marker on the position uluru 
        var marker = new google.maps.Marker({
          position: uluru,
          map: map
        });
      }
	</script>
    
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBKbMqsOpBmHpOgRWAX309V6jOq5fwfjwY&callback=initMap" >
    </script>
    

    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
    <script>
		var query = "weather+<?php print $_GET['city']; ?>+<?php print $weatherArray['weather'][0]['main'];?>";
		var API_KEY = "AIzaSyCCllQz6ZlQcx37-YSaejdy7I6YvHiDtUQ";
		var ENGINE_ID = "006306352685583418619:zce9fwxhe5c";
		var API_URL = `
		  https://www.googleapis.com/customsearch/v1?key=${API_KEY}&cx=${ENGINE_ID}&searchType=image&imgDominantColor=blue&q=${query}
		`
		// google.load("", "");

		$(document).ready(function() {

			$.getJSON(API_URL, {
					tags: query,
					tagmode: "any",
					format: "json"
				},
				function(data) {
					var rnd = Math.floor(Math.random() * data.items.length);

					var image_src = data.items[rnd]['link'];

					$('body').css('background-image', "url('" + image_src + "')");

				});

		});
    
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
  </body>
</html>
