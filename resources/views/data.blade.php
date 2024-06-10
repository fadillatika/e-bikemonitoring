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
        
        @vite(['resources/js/mapid.js', 'resources/js/battery.js', 'resources/js/lock.js'])

    </head>

    <body>
        <div class="hamburger" onclick="toggleSidebar()">
            <i data-feather="menu"></i>
        </div>
    <!-- Sidebar start -->
    @include('partials.userside')

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