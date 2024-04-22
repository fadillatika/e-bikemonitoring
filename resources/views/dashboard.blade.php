@extends('layouts.spesific')
<div class="main-content">
    <div class="flex-container">
            <!-- Map & Track Section -->
            <div class="map-track-section">
                <div class="map-container" id="myMap"></div>
            </div>
            <!-- History Table Section -->
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
                                <th>kiloWatt</th>
                                <th>Location</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($motor as $motors)
                                @php
                                    $batteries = $motors->batteries;
                                    $locks = $motors->locks;
                                    $trackings = $motors->trackings->take($batteries->count());
                                @endphp
                                                    
                                @foreach ($batteries as $index => $battery)
                                    @php
                                        $tracking = $trackings[$index] ?? null;
                                        $lock = $locks[$index] ?? null;
                                    @endphp
                                    <tr>
                                        <td>{{ $motors->motors_id }}</td>
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
            </div>
    </div>
</div>
<script>
    function GetMap() {
        var map = new Microsoft.Maps.Map(
            document.getElementById("myMap"),
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