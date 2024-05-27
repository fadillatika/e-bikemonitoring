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

        <!-- Turf -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Turf.js/6.5.0/turf.min.js"></script>

        <!-- Feather Icons -->
        <script src="https://unpkg.com/feather-icons"></script>

        <link rel="stylesheet" href="css/fitur.css" />
        
        <title>E-bike Monitoring!</title>
        
        @vite(['resources/js/mapid.js', 'resources/js/battery.js'])

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
                    <a href="/login" id="login">
                        <div class="menu-item">
                            <i data-feather="log-in"></i>
                            <span style="font-weight: bold">Login</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="/monitor" id="monitor">
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
                <li>
                    <a href="/info" id="info">
                        <div class="menu-item">
                            <i data-feather="info"></i>
                            <span style="font-weight: bold">Information</span>
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
            <div id="batteryPercentage" style="display: none;">
                @if($latestBatteryData){{ $latestBatteryData->percentage }}@else{{ 'N/A' }}@endif
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
            
            <!-- Distance -->
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
        </div>
        <!-- Map & Track Section -->
        <div class="card3 map-track-section2">
            <!-- Placeholder for Map -->
            <div class="map-container2" id="myMap2"></div>
            <script id="locationsForMap" type="application/json">@json($locationsForMap)</script>                                                                                         
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
                            <th>Location</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody">
                        @foreach ($motors as $motor)
                            @php
                                $batteries = $motor->batteries;
                                $locks = $motor->locks;
                                $trackings = $motor->trackings;
                                $lastLockStatus = 'Off';
                            @endphp
                                                
                            @foreach ($trackings as $index => $tracking)
                                @php
                                    $battery = $batteries[$index] ?? null;
                                    $lock = $locks[$index] ?? null;
                                    
                                    if ($lock) {
                                        $lastLockStatus = $lock->status ? 'On' : 'Off';
                                    }
                                    
                                    $dateToShow = $tracking ? $tracking->created_at : 'Data tidak ditemukan';
                                @endphp
                                <tr>
                                    <td>{{ $motor->motors_id }}</td>
                                    <td>{{ $dateToShow }}</td>
                                    <td>{{ $battery ? $battery->percentage . '%' : 'Data tidak ditemukan' }}</td>
                                    <td>{{ $battery ? $battery->kilometers . ' km' : 'Data tidak ditemukan' }}</td>
                                    <td>{{ $tracking ? $tracking->location_name : 'Lokasi tidak ditemukan' }}</td>
                                    <td>{{ $lastLockStatus }}</td>
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
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>
</body>
</html>