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

        <link rel="stylesheet" href="css/fitur.css" />
        <link rel="stylesheet" href="css/data.css" />
        
        <title>E-bike Monitoring!</title>
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    </head>

    <body>
        <div class="hamburger" onclick="toggleSidebar()">
            <i data-feather="menu"></i>
        </div>
    <!-- Sidebar start -->
    @include('partials.userside')
      <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.querySelector('.hamburger');
            const body = document.body;

            function toggleSidebar() {
                body.classList.toggle('sidebar-open');
                body.classList.toggle('sidebar-closed');
                console.log('Sidebar toggled');
            }

            sidebarToggle.addEventListener('click', function(e) {
                e.stopPropagation(); 
                toggleSidebar();
            });

            document.addEventListener('click', function(e) {
                if (body.classList.contains('sidebar-open') && !sidebar.contains(e.target)) {
                    toggleSidebar();
                }
            });

            sidebar.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            feather.replace();
        });
    </script>
    <script>
        feather.replace();
    </script>

    <!-- Java script -->
    <script src="js/fiturjs.js"></script>
</body>
</html>