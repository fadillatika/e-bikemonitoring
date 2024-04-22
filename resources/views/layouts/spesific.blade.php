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

        <link rel="stylesheet" href="css/dashboard.css" />
        
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
        @include ('partials.fitur2')
        
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
        
        <!-- Java script -->
        <script src="{{ asset('js/firebase.js') }}"></script>
        <script src="js/dashboard.js"></script>
    </body>
</html>