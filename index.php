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

		#controls {
			position: absolute;
			top: 0;
			width: 100%;
		}

		#stat {
			padding: 0 1em;
			position: absolute;
			width: 100%;
			height: 3em;
			bottom: 0;
			background: RGBA(0, 0, 0, 0.2);
		}
	</style>

	<script src="https://maps.googleapis.com/maps/api/js"></script>


	<script type="text/javascript">
		function initialize() {
			var map,
				marker,
				track,
				watchId;

			// Initialize the Google Maps API v3
			map = new google.maps.Map(document.getElementById('map'), {
				zoom: 15,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				center: {lat: 55.76, lng: 37.64}
			});

			function showPosition(position) {
				var accuracy_prv = document.getElementById('accuracy_prv'),
					timestamp_prv = document.getElementById('timestamp_prv'),
					accuracy = document.getElementById('accuracy'),
					timestamp = document.getElementById('timestamp'),
					time = new Date(position.timestamp);

				accuracy_prv.innerHTML = accuracy.innerHTML;
				timestamp_prv.innerHTML = timestamp.innerHTML;

				accuracy.innerHTML = Math.round(position.coords.accuracy);

				timestamp.innerHTML = time.getMinutes() + ':' + time.getSeconds() + ' ' + time.getMilliseconds() + 'ms';
			}

			function startWatch() {
				stopWatch();

				watchId = navigator.geolocation.watchPosition(function (position) {
					showPosition(position);
					var newPoint = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

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

					if (track) {
						// Add new point to track
						var path = track.getPath();
						path.push(newPoint);
					} else {
						//Create new track
						track = new google.maps.Polyline({
							path: [newPoint],
							map: map,
							clickable: false,
							strokeColor: '#FF0000',
							strokeOpacity: 1.0,
							strokeWeight: 2
						});
					}

					// Center the map on the new position
					map.setCenter(newPoint);
				}, function () {
					alert('Error');
				}, {
					enableHighAccuracy: true
				});
			}

			function stopWatch() {
				navigator.geolocation.clearWatch(watchId);
			}

			function initControls() {
				var startWatchBtn = document.getElementById('startWatchBtn'),
					stopWatchBtn = document.getElementById('stopWatchBtn'),
					newTrackBtn = document.getElementById('newTrackBtn');

				startWatchBtn.addEventListener('click', startWatch);

				stopWatchBtn.addEventListener('click', stopWatch);

				newTrackBtn.addEventListener('click', function() {
					if (track) {
						track.setMap(null);
						track = null;
					}
				});
			}

			initControls();
		}

		google.maps.event.addDomListener(window, 'load', initialize);

	</script>
</head>
<body>
<div id="map"></div>
<div id="stat">
	<!--latitude: <span id="latitude"></span>
	longitude: <span id="longitude"></span>-->
	accuracy: <span id="accuracy_prv"></span>
	timestamp: <span id="timestamp_prv"></span>
	<br>
	accuracy: <span id="accuracy"></span>
	timestamp: <span id="timestamp"></span>
</div>
<div id="controls">
	<button id="startWatchBtn">Start Watch</button>
	<button id="stopWatchBtn">Stop Watch</button>
	<button id="newTrackBtn">New track</button>
</div>
</body>
</html>