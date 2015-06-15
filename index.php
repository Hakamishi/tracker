<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name=viewport
		  content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<title>Tracker</title>
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;

		}

		html, body, #map {
			width: 100%;
			height: 100%;
		}
	</style>

	<script src="https://maps.googleapis.com/maps/api/js"></script>


	<script type="text/javascript">


		function initialize ()
		{
			var map;
			var marker = null;
			// Initialize the Google Maps API v3
			map = new google.maps.Map(document.getElementById('map'), {
				zoom: 15,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			});

			navigator.geolocation.watchPosition(function(position) {
				var newPoint = new google.maps.LatLng(position.coords.latitude,
					position.coords.longitude);

				if (marker) {
					// Marker already created - Move it
					marker.setPosition(newPoint);
				}
				else {
					// Marker does not exist - Create it
					marker = new google.maps.Marker({
						position: newPoint,
						map: map
					});
				}

				// Center the map on the new position
				map.setCenter(newPoint);
			}, function() {
				alert ('Error');
			}, {
				enableHighAccuracy: true
			});
		}

		google.maps.event.addDomListener(window, 'load', initialize);

	</script>
</head>
<body>
<div id="map"></div>
</body>
</html>