let map; // Deklarasi variabel map di luar agar dapat diakses oleh GetMap dan fungsi lainnya

function GetMap() {
    map = new Microsoft.Maps.Map('#myMap', {
        credentials: 'Your_Bing_Maps_API_Key', // Ganti dengan API key Anda
        center: new Microsoft.Maps.Location(47.606209, -122.332071), // Ganti dengan koordinat default atau dinamis
        zoom: 10
    });

    addPinsToMap();
}

function addPinsToMap() {
    // Tambahkan pin untuk setiap lokasi
    locationsForMap.forEach(function(location) {
        if(location && location.latitude && location.longitude) { // Memastikan data lokasi valid
            var loc = new Microsoft.Maps.Location(location.latitude, location.longitude);
            var pin = new Microsoft.Maps.Pushpin(loc, {
                title: "Motor ID: " + location.motor_id, // Menandai pin dengan Motor ID
                icon: '/path/to/motor-icon.png', // Sesuaikan atau hapus jika tidak menggunakan ikon khusus
            });
            map.entities.push(pin);
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    GetMap(); // Pastikan fungsi ini dipanggil setelah DOM sepenuhnya dimuat
});

