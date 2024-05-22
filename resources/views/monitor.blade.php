@extends('layouts.spesific')
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