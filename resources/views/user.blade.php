<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Racing+Sans+One&display=swap" />

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" crossorigin="" />

    <!-- Turf -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Turf.js/6.5.0/turf.min.js"></script>

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <link rel="stylesheet" href="css/fitur.css" />

    <title>E-bike Monitoring!</title>
    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

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
                <li>
                    <a href="/data" id="data">
                        <div class="menu-item">
                            <i data-feather="folder"></i>
                            <span style="font-weight: bold">Data</span>
                        </div>
                    </a>
                </li>
                <li style="text-align: center;">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline-block;">
                        @csrf
                        <button type="submit"
                            style="background: none; border: none; padding: 0; color: inherit; text-decoration: inherit; cursor: pointer;">
                            <div class="menu-item">
                                <i data-feather="log-out"></i>
                                <span style="font-weight: bold;">Logout</span>
                            </div>
                        </button>
                    </form>
                </li>
                <li>
                    <div class="menu-item search-bar">
                        <form action="{{ route('user.search') }}" method="get">
                            <input type="text" name="q" placeholder="Search ID" />
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
                    @if($motors->isEmpty())
                    <h2>Data not found for this motor.</h2>
                    @else
                    @foreach ($motors as $motor)
                    <h2>{{ $motor->motors_id }}</h2>
                    @endforeach
                    @endif
                </div>
            </div>
            <!-- Battery -->
            <div class="card2 battery-status">
                <div class="battery-title">Battery</div>
                @if(isset($dataNotFound) && $dataNotFound)
                <div class="battery-error" style="display: block; margin-top: 25px;">-</div>
                @else
                <div class="battery-display" style="display: block;">
                    <div class="battery-container">
                        <div class="battery-head"></div>
                        <div class="battery-body">
                            <div class="battery-indicator"></div>
                        </div>
                    </div>
                    <div class="battery-content">
                        <div class="battery-info">
                            <div class="battery-stats">
                                <span class="battery-percentage">@if($latestBatteryData){{
                                    $latestBatteryData->percentage }}% @else - @endif</span>
                                <!--saat data yang diterima berupa angka -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="battery-error"
                    style="display: none; margin-top: 20px; font-size: 2.5em; font-weight: bold;">-</div>
                @endif
            </div>
            <div id="batteryPercentage" style="display: none;">
                @if($latestBatteryData){{ $latestBatteryData->percentage }}@else{{ 'N/A' }}@endif
                <!--saat data yang diterima bukan berupa angka -->
            </div>
            <!-- Distance -->
            <div class="card2 Time">
                <h2>Distance Estimate</h2>
                <img src="img/distance.png" alt="distance"
                    style="width: 80px; height: auto; margin-top: 10px; margin-left: 25px;">
                <div class="battery-content">
                    <div class="battery-info">
                        <div class="battery-stats">
                            <span class="battery-kilometers">@if($latestBatteryData){{ $latestBatteryData->kilometers
                                }}km @else - @endif</span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="batteryKilometers" style="display: none;">
                @if($latestBatteryData){{ $latestBatteryData->kilometers }}@else{{ 'N/A' }}@endif
            </div>
            <!-- Wheel Lock -->
            <div class="card2 wheel-lock">
                <h2>Wheel Lock Status</h2>
                <div id="lockStatus"></div>
                @if($latestLock)
                <h3>Status : <span id="lockStatusText">{{ $latestLock->status ? 'Unlocked' : 'Locked' }}</span></h3>
                <span class="lock-icon">
                    @if($latestLock->status)
                    <i id="lockIcon" data-feather="unlock"></i>
                    @else
                    <i id="lockIcon" data-feather="lock"></i>
                    @endif
                </span>
                <button id="lockButton" class="lock-button {{ $latestLock->status ? 'on' : 'off' }}"
                    onclick="toggleLockStatus()" {{ $latestLock->status ? 'data-status="on"' : 'data-status="off"'
                    }}>
                    {{ $latestLock->status ? 'ON' : 'OFF' }}
                </button>
            </div>
            @else
            <h3 style="margin-top: 40px; font-size: 2.5em; font-weight: bold;">-</h3>
            @endif
        </div>
    </div>
    <!-- Map & Track Section -->
    <div class="card3 map-track-section2">
        <div class="control-buttons">
            <button id="startStopButton" class="btn btn-success">Start Tracking</button>
            <button id="resetButton" class="btn btn-warning" style="display:none;">Reset</button>
        </div>
        <!-- Placeholder for Map -->
        <div class="map-container2" id="myMap2"></div>
        <script id="locationsForMap" type="application/json">@json($locationsForMap)</script>
        <div id="totalDistance" style="display:none;"></div>
    </div>
    <script>
        feather.replace();
    </script>

    <!-- Java script -->
    <script src="js/fiturjs.js"></script>
    <script src="js/notification.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" crossorigin=""></script>

</body>

</html>