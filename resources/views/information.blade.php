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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <script src="https://unpkg.com/feather-icons"></script>

    <!-- More Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css"
        integrity="sha384-wESLQ85D6gbsF459vf1CiZ2+rr+CsxRY0RpiF1tLlQpDnAgg6rwdsUF1+Ics2bni" crossorigin="anonymous" />

    <link rel="stylesheet" href="css/forgot-password.css" />
    <!-- <link rel="stylesheet" href="css/login.css" /> -->

    <title>E-bike Monitoring!</title>
</head>

<body>
    <!-- Navbar start -->
    <nav class="navbar">
        <a class="navbar-logo" href="/home">
            <img src="img/logo.png" alt="Logo" width="135">
        </a>
        <div class="navbar-nav">
            <a href="/about"><i data-feather="users"></i> About</a>
            <a href="/information"><i data-feather="info"></i> Information</a>
            <a href="/login" id="login"><i data-feather="log-in"></i> Login</a>
        </div>
        <div class="navbar-extra">
            <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>
    </nav>

    <div class="image-container">
        <a href="https://bit.ly/e-Bikemonitoring">
            <img src="{{ asset('img/apk.png') }}" alt="qrcode" class="small-image" />
        </a>
    </div>

    <footer class="site-footer">
        <div class="footer-container">
            <p>Copyright &copy;2024; Designed by Telkom University</p>
        </div>
    </footer>
    </main>
    </section>
    <script>
        feather.replace();
    </script>

    <!-- Java script -->
    <script src="js/script.js"></script>
</body>

</html>