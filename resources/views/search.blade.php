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
        <script
            type="text/javascript"
            src="https://www.bing.com/api/maps/mapcontrol?key=Ao8xqO0T79i47wspdw8nKPcCymMd68PFqI9PuUS2Oeo5djho34g_m1tYelh4r9xE&callback=GetMap"
            async
            defer
        ></script>
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
                    <a href="/about" id="about">
                        <div class="menu-item">
                            <i data-feather="users"></i>
                            <span style="font-weight: bold">About</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="/" id="home">
                        <div class="menu-item">
                            <i data-feather="home"></i>
                            <span style="font-weight: bold">Home Page</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="/dashboard" id="grid">
                        <div class="menu-item">
                            <i data-feather="grid"></i>
                            <span style="font-weight: bold">Dashboard</span>
                        </div>
                    </a>
                </li>
                <li>
                    <div class="menu-item search-bar">
                        <form action="{{ route('search') }}" method="get">
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
                                    <!-- Tinggi diatur sesuai persentase -->
                                </div>
                            </div>
                            <div class="battery-content">
                                <div class="battery-info">
                                    <span class="battery-kilometers">@if($latestBatteryData){{ $latestBatteryData->kilometers }}km @else - @endif</span>
                                    <div class="battery-separator"></div>
                                    <div class="battery-stats">
                                        <span class="battery-percentage">@if($latestBatteryData){{ $latestBatteryData->percentage }}% @else - @endif</span>
                                        <span class="battery-power">@if($latestBatteryData){{ $latestBatteryData->kW }}kW @else - @endif</span>
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
                        <label class="switch">
                            <input type="checkbox" {{ $latestLock->status ? 'checked' : '' }} onclick="return false;">
                            <span class="slider round"></span>
                        </label>
                    </div>
                @else
                    <h2>Wheel Lock Status</h2>
                    <h3 style="margin-top: 40px;">Data wheel lock tidak ditemukan.</h3>
                @endif
            </div> 
            
            <!-- Date & Time Info -->
            <div class="card2 Time">
                <h2>Date and Time</h2>
                <div id="date-box" class="box">
                    <div id="current-date" class="date"></div>
                </div>
                <div id="time-box" class="box">
                    <div id="current-time" class="time"></div>
                    <div class="wib"></div>
                </div>
            </div>

            <script>
                function updateDateTime() {
                    var now = new Date();

                    // Format waktu menjadi HH:MM:SS
                    var hours = now.getHours().toString().padStart(2, "0");
                    var minutes = now.getMinutes().toString().padStart(2, "0");
                    var seconds = now.getSeconds().toString().padStart(2, "0");
                    var timeString =
                        hours + ":" + minutes + ":" + seconds + " WIB";

                    // Format tanggal menjadi DD-MM-YYYY
                    var day = now.getDate().toString().padStart(2, "0");
                    var month = (now.getMonth() + 1)
                        .toString()
                        .padStart(2, "0"); // Bulan dimulai dari 0
                    var year = now.getFullYear();
                    var dateString = day + "-" + month + "-" + year;

                    document.getElementById("current-time").innerText =
                        timeString;
                    document.getElementById("current-date").innerText =
                        dateString;
                }

                setInterval(updateDateTime, 1000);
                updateDateTime();
            </script>
        </div>
        <!-- Map & Track Section -->
        <div class="card3 map-track-section2">
            <!-- Placeholder for Map -->
            <div class="map-container2" id="myMap2"></div>

            <!-- Track Information -->
            @php
            if ($motors->isNotEmpty()) {
                $firstMotor = $motors->first();
                $batteries = $firstMotor->batteries;
                $locks = $firstMotor->locks;
                $trackings = $firstMotor->trackings->take($batteries->count());
                $firstTracking = $trackings->first();
            } else {
                $firstTracking = null;
            }
            @endphp
        
            <div class="track-information2">
                <h2>Track Information</h2>
                <div class="track-details2">
                    @if ($firstTracking)
                        <p>{{ $firstTracking->location_name }}</p>
                        <p>{{ $firstTracking->address }}</p>
                        <p>{{ $firstTracking->kilometers }}, {{ $firstTracking->duration }}</p>
                    @else
                        <p>Lokasi tidak ditemukan</p>
                    @endif
                </div>
            </div>
        
        </div>

        <!-- History Table Section -->
        <div class="card3 history-table2">
            <h2 style="text-align: justify;">History Table</h2>
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
                                @endphp
                                <tr>
                                    <td>{{ $motor->motors_id }}</td>
                                    <td>{{ $battery->last_charged_at }}</td>
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
        function GetMap() {
            var map = new Microsoft.Maps.Map(
                document.getElementById("myMap2"),
                {
                    credentials: "Ao8xqO0T79i47wspdw8nKPcCymMd68PFqI9PuUS2Oeo5djho34g_m1tYelh4r9xE&callback", // Use your Bing Maps Key
                    center: new Microsoft.Maps.Location(
                        47.606209,
                        -122.332071
                    ), // Example coordinates
                    zoom: 10,
                }
            );
        }
    </script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const sidebar = document.querySelector('.sidebar');
                const sidebarToggle = document.querySelector('.hamburger');
                const body = document.body;

                // Fungsi untuk toggle class 'sidebar-open' dan 'sidebar-closed'
                function toggleSidebar() {
                    body.classList.toggle('sidebar-open');
                    body.classList.toggle('sidebar-closed');
                    console.log('Sidebar toggled');
                }

                // Event listener untuk hamburger menu
                sidebarToggle.addEventListener('click', function(e) {
                    e.stopPropagation(); // Menghentikan event klik dari "bubbling up"
                    toggleSidebar();
                });

                // Event listener untuk menutup sidebar jika klik diluar sidebar
                document.addEventListener('click', function(e) {
                    if (body.classList.contains('sidebar-open') && !sidebar.contains(e.target)) {
                        toggleSidebar();
                    }
                });

                // Menghentikan event klik dari sidebar agar tidak menutup sidebar
                sidebar.addEventListener('click', function(e) {
                    e.stopPropagation();
                });

                // Menggantikan icon feather (jika Anda menggunakan feather icons)
                feather.replace();
            });
        </script>        
            
    <script>
        feather.replace();
    </script>

    <!-- Java script -->
    <script src="js/fiturjs.js"></script>
</body>
</html>