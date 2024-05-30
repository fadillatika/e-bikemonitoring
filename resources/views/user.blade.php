<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}" />
        <!-- Google Fonts -->
        <link
            href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap"
            rel="stylesheet"
        />
        <link
            href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap"
            rel="stylesheet"
        />
        <link
            href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap"
            rel="stylesheet"
        />
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Racing+Sans+One&display=swap"
        />

        <!-- Leaflet CSS -->
        <link
            rel="stylesheet"
            href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        />

        <!-- Feather Icons -->
        <script src="https://unpkg.com/feather-icons"></script>

        <link rel="stylesheet" href="css/fitur.css" />
        
        <title>E-bike Monitoring!</title>
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    </head>

    <body>
        <div class="hamburger" onclick="toggleSidebar()">
            <i data-feather="menu"></i>
        </div>
    <!-- Sidebar start -->
    <aside class="sidebar">
        <a class="sidebar-logo" href="/home">
            <img src="/img/logo.png" alt="Logo" />
        </a>
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="/account" id="account">
                        <div class="menu-item">
                            <i data-feather="user"></i>
                            <span style="font-weight: bold">Account</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="/monitoruser" id="monitor">
                        <div class="menu-item">
                            <i data-feather="monitor"></i>
                            <span style="font-weight: bold">Monitoring <br>& Tracking</br></span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="/about" id="about">
                        <div class="menu-item">
                            <i data-feather="users"></i>
                            <span style="font-weight: bold">About</span>
                        </div>
                    </a>
                </li>
                <li style="text-align: center;">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline-block;">
                        @csrf
                        <button type="submit" style="background: none; border: none; padding: 0; color: inherit; text-decoration: inherit; cursor: pointer;">
                            <a class="menu-item" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                                <i data-feather="log-out"></i>
                                <span style="font-weight: bold;">Logout</span>
                            </a>
                        </button>
                    </form>
                </li>
                <li>
                    <div class="menu-item search-bar">
                        <form action="{{ route('user.search') }}" method="get">
                            <input
                                type="text"
                                name="q"
                                placeholder="Search ID"
                            />
                            <button type="submit">
                                <i data-feather="search"></i>
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </aside>
    <!-- Main content area -->
    <div class="new-flex-container">
        <div id="halamanDenganScroll" class="main-content2">
            <!-- Speedometer -->
            <div class="card2 speedometer">
                <h2>Motor ID</h2>
                <div id="boxID" class="box">
                    @foreach ($motors as $motor)
                    <h2>{{ $motor->motors_id }}</h2>
                    @endforeach
                </div>
            </div>
                <!-- Battery Status -->
                <div class="card2 battery-status">
                    <div class="battery-title">Battery</div>
                    @if(isset($dataNotFound) && $dataNotFound)
                        <div class="battery-error" style="display: block; margin-top: 25px;">Tidak ditemukan data</div>
                    @else
                        <div class="battery-display" style="display: none;">
                            <div class="battery-container">
                                <div class="battery-head"></div>
                                <div class="battery-body">
                                    <div class="battery-indicator"></div>
                                </div>
                            </div>
                            <div class="battery-content">
                                <div class="battery-info">
                                    <div class="battery-stats">
                                        <span class="battery-percentage">@if($latestBatteryData){{ $latestBatteryData->percentage }}% @else - @endif</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="battery-error" style="display: none; margin-top: 40px;">Tidak ditemukan data</div>
                    @endif
                </div>
                
                <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const rawBatteryPercentage = "@if($latestBatteryData){{ $latestBatteryData->percentage }}@else{{ 'N/A' }}@endif";
                    const batteryDisplays = document.querySelectorAll(".battery-display");
                    const batteryErrors = document.querySelectorAll(".battery-error");
                
                    function updateBatteryDisplay(batteryDisplay, percentage) {
                        const batteryIndicator = batteryDisplay.querySelector(".battery-indicator");
                        if (!isNaN(percentage) && percentage !== 'N/A') {
                            batteryIndicator.style.height = percentage + "%";
                            if (percentage <= 20) {
                                batteryIndicator.style.background = "linear-gradient(to right, red, orange)";
                            } else if (percentage <= 49) {
                                batteryIndicator.style.background = "linear-gradient(to right, orange, yellow)";
                            } else {
                                batteryIndicator.style.background = "linear-gradient(to right, green, lime)";
                            }
                        }
                    }
                
                    function toggleBatteryData(isAvailable) {
                        batteryDisplays.forEach((display, index) => {
                            if (isAvailable && rawBatteryPercentage !== 'N/A') {
                                display.style.display = "block";
                                updateBatteryDisplay(display, parseFloat(rawBatteryPercentage));
                                if (batteryErrors[index]) batteryErrors[index].style.display = "none";
                            } else {
                                display.style.display = "none";
                                if (batteryErrors[index]) batteryErrors[index].style.display = "block";
                            }
                        });
                    }
                
                    if(rawBatteryPercentage !== 'N/A' && !isNaN(parseFloat(rawBatteryPercentage))) {
                        toggleBatteryData(true);
                    } else {
                        toggleBatteryData(false);
                    }
                });
                </script> 
            <!-- Date & Time Info -->
            <div class="card2 Time">
                <h2>Distance Estimate</h2>
                <img src="img/distance.png" alt="distance" style="width: 80px; height: auto; margin-top: 10px; margin-left: 25px;">
                <div class="battery-content">
                    <div class="battery-info">
                        <div class="battery-stats">
                            <span class="battery-kilometers">@if($latestBatteryData){{ $latestBatteryData->kilometers }}km @else - @endif</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Wheel Lock -->
            <div class="card2">
                @if($latestLock)
                    <div>
                        <h2>Wheel Lock Status</h2>
                        <h3>Status : {{ $latestLock->status ? 'Unlocked' : 'Locked' }}</h3>
                        <span class="lock-icon">
                            @if($latestLock->status)
                                <i data-feather="unlock"></i>
                            @else
                                <i data-feather="lock"></i>
                            @endif
                        </span>
                        <button id="lockButton" class="lock-button {{ $latestLock->status ? 'on' : 'off' }}" onclick="toggleLockStatus()" {{ $latestLock->status ? 'data-status="on"' : 'data-status="off"' }}>
                            {{ $latestLock->status ? 'ON' : 'OFF' }}
                        </button>
                    </div>
                @else
                    <h2>Wheel Lock Status</h2>
                    <h3 style="margin-top: 40px;">Data wheel lock tidak ditemukan.</h3>
                @endif
            </div> 
        </div>
        <!-- Map & Track Section -->
        <div class="card3 map-track-section2">
            <!-- Placeholder for Map -->
            <div class="map-container2" id="myMap2"></div>
            <script>
                var map = L.map('myMap2');
            
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                
                var locations = @json($locationsForMap);
                var bounds = [];
                var routePoints = [];
                
                var customIcon = L.icon({
                    iconUrl: 'img/electric-motorcycle.png',
                    iconSize: [50, 50],
                    iconAnchor: [25, 50],
                    popupAnchor: [0, -40]
                });
                
                if (locations.length >= 2) {
                    locations.forEach(function(location) {
                        routePoints.push([location.lat, location.lng]);
                    });
                
                    var totalDistanceKilometers = calculateTotalDistance(routePoints);
                
                    var firstLocation = locations[0];
                    var lastLocation = locations[locations.length - 1];
                
                    var popupContentFirst = "<br><b>Motor ID:</b> " + firstLocation.motorName + "<br><b>Location:</b> " + firstLocation.name + "<br><b>Total Distance:</b> " + totalDistanceKilometers.toFixed(2) + " km";
                    var markerFirst = L.marker([firstLocation.lat, firstLocation.lng], {icon: customIcon}).addTo(map)
                        .bindPopup(popupContentFirst);
                    bounds.push([firstLocation.lat, firstLocation.lng]);
                
                    var popupContentLast = "<br><b>Motor ID:</b> " + lastLocation.motorName + "<br><b>Location:</b> " + lastLocation.name;
                    var markerLast = L.marker([lastLocation.lat, lastLocation.lng], {icon: customIcon}).addTo(map)
                        .bindPopup(popupContentLast);
                    bounds.push([lastLocation.lat, lastLocation.lng]);
                }
                
                if (bounds.length > 0) {
                    map.fitBounds(bounds);
                }
                
                if (routePoints.length > 1) {
                    getRoute(routePoints, function(geometry) {
                        var route = L.geoJSON(geometry, {
                            style: { color: 'blue' }
                        }).addTo(map);
                        map.fitBounds(route.getBounds());
                    });
                }
                
                function getRoute(points, callback) {
                    const coordinates = points.map(p => `${p[1]},${p[0]}`).join(';');
                    fetch(`https://router.project-osrm.org/route/v1/driving/${coordinates}?overview=full&geometries=geojson`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.routes && data.routes.length > 0) {
                                callback(data.routes[0].geometry);
                            } else {
                                console.error('No route found');
                            }
                        })
                        .catch(error => console.error('Error fetching route:', error));
                }
                
                function calculateTotalDistance(points) {
                    let totalDistance = 0;
                    for (let i = 0; i < points.length - 1; i++) {
                        const pointA = points[i];
                        const pointB = points[i + 1];
                        totalDistance += calculateDistance(pointA, pointB);
                    }
                    return totalDistance;
                }
                
                function calculateDistance(pointA, pointB) {
                    const R = 6371; 
                    const lat1 = pointA[0] * Math.PI / 180;
                    const lat2 = pointB[0] * Math.PI / 180;
                    const deltaLat = (pointB[0] - pointA[0]) * Math.PI / 180;
                    const deltaLon = (pointB[1] - pointA[1]) * Math.PI / 180;
                
                    const a = Math.sin(deltaLat / 2) * Math.sin(deltaLat / 2) +
                            Math.cos(lat1) * Math.cos(lat2) *
                            Math.sin(deltaLon / 2) * Math.sin(deltaLon / 2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                
                    return R * c;
                }
            </script>                       
        </div>

        <!-- History Table Section -->
        <div class="card3 history-table2">
            <h2 style="text-align: justify;">History Table</h2>
            <form class="download-form" action="{{ route('downloadData') }}" method="GET">
                <label class="download-label" for="start_date">Select Date:</label>
                <input class="download-input" type="date" id="start_date" name="start_date">
            
                <label class="download-label" for="end_date">to</label>
                <input class="download-input" type="date" id="end_date" name="end_date">
            
                <button class="download-button" type="submit">Download</button>
            </form>
            @if($motors->isEmpty())
                <div class="table-responsive">
                    <p style="text-align: center; color: #fff; padding: 20px;">Tidak ada data yang tersedia.</p>
                </div>
            @else
            <div class="table-responsive">
                <table class="history-data-table2">
                    <thead>
                        <tr>
                            <th>ID E-bike</th>
                            <th>Date</th>
                            <th>Percentage</th>
                            <th>Battery-Kilometers</th>
                            <th>kiloWatt</th>
                            <th>Location</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($motors as $motor)
                            @php
                                $batteries = $motor->batteries;
                                $locks = $motor->locks;
                                $trackings = $motor->trackings->take($batteries->count());
                            @endphp
                                                
                            @foreach ($batteries as $index => $battery)
                                @php
                                    $tracking = $trackings[$index] ?? null;
                                    $lock = $locks[$index] ?? null;
                                    $dateToShow = $tracking ? $tracking->updated_at : 'Data tidak ditemukan';
                                @endphp
                                <tr>
                                    <td>{{ $motor->motors_id }}</td>
                                    <td>{{ $dateToShow }}</td>
                                    <td>{{ $battery->percentage }}%</td>
                                    <td>{{ $battery->kilometers }} km</td>
                                    <td>{{ $battery->kW }} kW</td>
                                    <td>{{ $tracking ? $tracking->location_name : 'Lokasi tidak ditemukan' }}</td>
                                    <td>{{ $lock ? ($lock->status ? 'On' : 'Off') : 'Status lock tidak ditemukan' }}</td>
                                </tr>
                            @endforeach
                        @endforeach                
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    <script>
        window.onload = function() {
            var table = document.querySelector(".history-data-table2");
            var tbody = table.querySelector("tbody");
            if (tbody.scrollWidth > tbody.clientWidth) {
                var scrollbarWidth = tbody.offsetWidth - tbody.clientWidth;
                table.querySelector("thead").style.paddingRight = scrollbarWidth + "px";
            }
        };
        </script>
            
    <script>
        feather.replace();
    </script>

    <!-- Java script -->
    <script src="js/fiturjs.js"></script>
</body>
</html>