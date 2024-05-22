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

        <link rel="stylesheet" href="css/dashboard.css" />
        
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
            <a class="sidebar-logo" href="{{ route('user.search', ['q' => session('motor_id')]) }}">
                <img src="/img/logo.png" alt="Logo" />
            </a>
            <div class="sidebar-menu">
                <ul>
                    <li>
                        <a href="/account" id="user">
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
        <div class="main-content">
            <div class="flex-container">
                <div class="map-track-section">
                    <div class="map-container" id="myMap"></div>
                </div>
                <div class="history-table">
                    <h2>History Table</h2>
                    <div class="table-container">
                        <table class="history-data-table">
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
                            <tbody>
                                @foreach ($motor as $motors)
                                    @php
                                        $batteries = $motors->batteries;
                                        $locks = $motors->locks;
                                        $trackings = $motors->trackings;
                                        $lastLockStatus = 'Off'; // Default status
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
                                            <td>{{ $motors->motors_id }}</td>
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
                </div>
            </div>
        </div>
        <script src="js/dash.js"></script>
        <script src="js/dashboard.js"></script>
    </body>
</html>