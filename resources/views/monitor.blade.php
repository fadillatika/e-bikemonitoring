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
                                        $dateToShow = $tracking ? $tracking->created_at : 'Data tidak ditemukan';
                                    @endphp
                                    <tr>
                                        <td>{{ $motors->motors_id }}</td>
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
            </div>
    </div>
</div>