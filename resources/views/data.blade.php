<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Racing+Sans+One&display=swap" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="css/data.css">
    <link rel="stylesheet" href="css/date.css">
    <title>E-bike Monitoring!</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>

<body>
    <div class="hamburger" onclick="toggleSidebar()">
        <i data-feather="menu"></i>
    </div>

    @include ('partials.userside')

    <div class="main-content">
        <div class="flex-container">
            <div class="history-table">
                <form class="download-form" action="{{ route('downloadTrackingData') }}" method="GET">

                    <label class="download-label" for="motor_id">Motor ID:</label>
                    <input class="download-input" type="text" id="motor_id" name="motor_id" required>

                    <label class="download-label" for="start_date">Start Date:</label>
                    <input class="download-input" type="date" id="start_date" name="start_date">

                    <label class="download-label" for="end_date">End Date:</label>
                    <input class="download-input" type="date" id="end_date" name="end_date">

                    <button class="download-button" type="submit">Download Tracking Data</button>
                </form>

                <h2>Location Data Table</h2>
                <div class="table-container">
                    <table class="history-data-table">
                        <thead>
                            <tr>
                                <th>ID E-bike</th>
                                <th>Date</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Location</th>
                                <th>Distance (km)</th>
                                <th>Total Distance (km)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($motor as $motorItem)
                            @php
                            $prevTracking = null;
                            @endphp
                            @foreach ($motorItem->trackings as $tracking)
                            @if (!$prevTracking || $tracking->latitude != $prevTracking->latitude ||
                            $tracking->longitude != $prevTracking->longitude)
                            <tr>
                                <td>{{ $motorItem->motors_id }}</td>
                                <td>{{ $tracking->updated_at }}</td>
                                <td>{{ $tracking->latitude }}</td>
                                <td>{{ $tracking->longitude }}</td>
                                <td>{{ $tracking->location_name }}</td>
                                <td>{{ $tracking->distance }}</td>
                                <td>{{ $tracking->total_distance }}</td>
                            </tr>
                            @endif
                            @php
                            $prevTracking = $tracking;
                            @endphp
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <form class="download-form" action="{{ route('downloadBatteryData') }}" method="GET">

                    <label class="download-label" for="motor_id">Motor ID:</label>
                    <input class="download-input" type="text" id="motor_id" name="motor_id" required>

                    <label class="download-label" for="start_date">Start Date:</label>
                    <input class="download-input" type="date" id="start_date" name="start_date">

                    <label class="download-label" for="end_date">End Date:</label>
                    <input class="download-input" type="date" id="end_date" name="end_date">

                    <button class="download-button" type="submit">Download Battery Data</button>
                </form>

                <h2>Battery Data Table</h2>
                <div class="table-container">
                    <table class="history-data-table">
                        <thead>
                            <tr>
                                <th>ID E-bike</th>
                                <th>Date</th>
                                <th>Voltage</th>
                                <th>Percentage <br>Motor Battery</th>
                                <th>Distance Estimate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($motor as $motorItem)
                            @foreach ($motorItem->batteries as $key => $battery)
                            @if ($key == 0 || $battery->percentage != $motorItem->batteries[$key - 1]->percentage)
                            <tr>
                                <td>{{ $motorItem->motors_id }}</td>
                                <td>{{ $battery->updated_at }}</td>
                                <td>{{ $battery->voltage }}</td>
                                <td>{{ $battery->percentage }}%</td>
                                <td>{{ $battery->kilometers }} km</td>
                            </tr>
                            @endif
                            @endforeach
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="js/dashboard.js"></script>
</body>

</html>