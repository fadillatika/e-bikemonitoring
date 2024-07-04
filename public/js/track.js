var map = L.map('map').setView([0, 0], 15);
		
		//https://github.com/pointhi/leaflet-color-markers
        var blueIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
        var redIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
		
		var greenIcon = new L.Icon({
			iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
			shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
			iconSize: [25, 41],
			iconAnchor: [12, 41],
			popupAnchor: [1, -34],
			shadowSize: [41, 41]
		});

		var orangeIcon = new L.Icon({
			iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png',
			shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
			iconSize: [25, 41],
			iconAnchor: [12, 41],
			popupAnchor: [1, -34],
			shadowSize: [41, 41]
		});
		
        var initialMarker = L.marker([0, 0], {icon: redIcon});
        var updatedMarker = L.marker([0, 0], {icon: blueIcon});
        var finishMarker = L.marker([0, 0], {icon: greenIcon}); 
        var polyline = L.polyline([], { color: 'blue' });
        var startPolylineButton = document.getElementById('startPolylineButton');
		var resetButton = document.getElementById('resetButton');
        var isPolylineStarted = false;
        var locationData = [];
        var currentIndex = 0;
        var polylineIndex = 0;
		var stat_btn = 0;
		
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

		
		function updateMap(data) {
			// Extract latitude and longitude from the API response
			var latitude = parseFloat(data.field1);
			var longitude = parseFloat(data.field2);
			var hum  = parseFloat(data.field3);
			var temp = parseFloat(data.field4);
			var batt = parseFloat(data.field5);
			var timestamp = data.created_at;
			hum  = hum.toFixed(2);
			temp = temp.toFixed(2);
			batt = batt.toFixed(2);
			
			var date_ = timestamp.split('T'), //buat split tanggal sama jam ==> 2020-03-11T21:32:27+07:00
				date_now = date_[0], time_z = date_[1];
			var time_ = time_z.split('+'), time_now=time_[0]; // buat menghilangkan GMT +7
			
			document.getElementById('latitude').textContent =  latitude.toFixed(6);
            document.getElementById('longitude').textContent =  longitude.toFixed(6);
			document.getElementById('time_date').textContent =  date_now;
            document.getElementById('time_time').textContent =  time_now;
			document.getElementById('temperature').textContent =  temp;
            document.getElementById('humidity').textContent =  hum;
			document.getElementById('battery').textContent =  batt;

			// Update markers
			updateMarker(hum, temp, batt, latitude, longitude, date_now, time_now);
			if (stat_btn == 1) {
				// Update polyline
				polyline.addLatLng([latitude, longitude]);
				//polyline.setLatLngs([latitude, longitude]);
				polyline.addTo(map);
			}

			// Center the map on the updated location
			map.panTo([latitude, longitude]);
		}
		
		function updateMarker(hum, temp, batt, latitude, longitude, date_now, time_now) {
		    var customPopup="<b>Stored Data<br>"+date_now+" "+time_now+"</b><br><b>Temperature: </b>"+temp+" Celcius<br><b>Humidity: </b>"+hum+" %<br><b>Battery: </b>"+batt+" %";
            var marker;// = isPolylineStarted ? updatedMarker.addTo(map) : initialMarker.addTo(map);
			
			if (stat_btn == 0) {
				marker = initialMarker.addTo(map);
            }
			if (stat_btn == 1){
				marker = updatedMarker.addTo(map);
            }
			if (stat_btn == 2){
				marker = finishMarker.addTo(map);
            }
            marker.setLatLng([latitude, longitude]).update();
            //marker.bindPopup(`Timestamp: ${timestamp}`);
			marker.bindPopup(customPopup).addTo(map);
            map.setView([latitude, longitude], map.getZoom());
        }
		
		function updatePolyline(data) {
            var coordinates = data.map(location => [location.lat, location.lng]);
            polyline.setLatLngs(coordinates);
            if (stat_btn == 1) {
                polyline.addTo(map);
            }
        }
		
        function startPolyline() {
			if (stat_btn == 0) {
                //isPolylineStarted = true;
				stat_btn=1;
                polylineIndex = currentIndex;
                startPolylineButton.textContent = 'Stop';
                startPolylineButton.classList.remove('btn-success');
                startPolylineButton.classList.add('btn-danger');
				resetButton.style.display = 'none'; // Hide reset button when starting polyline
            } else if (stat_btn == 1){
                //isPolylineStarted = false;
				stat_btn=2;
                startPolylineButton.textContent = 'Start Tracking';
                startPolylineButton.classList.remove('btn-danger');
                startPolylineButton.classList.add('btn-success');
				startPolylineButton.disabled = true;
				resetButton.style.display = 'block'; // Show reset button when stopping polyline
            }else{
				stat_btn=0;
                startPolylineButton.textContent = 'Start Polyline';
                startPolylineButton.classList.remove('btn-default');
                startPolylineButton.classList.add('btn-success');
			}
			console.log('Button Status:', stat_btn); 
		
        }
		
		function resetMap() {
			stat_btn=0;
            map.eachLayer(function (layer) {
                if (layer instanceof L.Marker || layer instanceof L.Polyline) {
                    map.removeLayer(layer);
                }
            });
			startPolylineButton.disabled = false;
			document.getElementById('latitude').textContent = "";
            document.getElementById('longitude').textContent = "";
			document.getElementById('time_date').textContent =  "";
            document.getElementById('time_time').textContent =  "";
			document.getElementById('temperature').textContent =  "";
            document.getElementById('humidity').textContent =  "";
			document.getElementById('battery').textContent =  "";
            initialMarker = L.marker([0, 0], {icon: redIcon});
			updatedMarker = L.marker([0, 0], {icon: blueIcon});
			finishMarker = L.marker([0, 0], {icon: greenIcon}); 
            polyline = L.polyline([], { color: 'blue' });
            resetButton.style.display = 'none';
            fetchData(); // Fetch new location data after reset
        }

		function fetchData() {
			// Make an API request to obtain location data
			fetch('https://api.thingspeak.com/channels/XXXXXXX/feeds/last.json?timezone=Asia%2FJakarta&api_key=XXXXXXXXXX')
			  .then(response => response.json())
			  .then(data => {
				updateMap(data);
			  })
			  .catch(error => console.error('Error fetching data:', error));
		}

		// Update the map every X milliseconds
		setInterval(fetchData, 5000); // Set the interval according to your requirements

		// Initial fetch when the page loads
		fetchData();

        startPolylineButton.addEventListener('click', startPolyline);
		resetButton.addEventListener('click', resetMap);