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
            <a href="/info" id="info"><i data-feather="info"></i> Information</a>
        </div>
        <div class="navbar-extra">
            <a href="/login" id="login"><i data-feather="log-in"></i> Login</a>
            <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>
    </nav>
    <!-- Hero Section -->
    <section class="hero" id="home" style="
                min-height: 115vh;
                display: flex;
                align-items: center;
                background-image: url('img/bg.png');
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
                position: relative;
                text-align: center;
                overflow: hidden;
            ">
        <main class="content-row">
            <div class="header-content">
                <h1> Electrical Bike <span>Monitoring</span></h1>
                <img src="img/motor.png" alt="icon motor" class="motor-icon">
            </div>

            <div class="hero-side">
                <div class="login-container">
                    <span class="title"> Forgot Password </br></span>
                    <span class="note"> We will send a link to your email, use that link to reset password.</span>

                    @if(session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(session()->has('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                    @endif

                    <form action="{{ route('forgot.passwordpost') }}" method="post">
                        @csrf
                        <div class="input-group">
                            <input type="email" name="email" placeholder="Enter your email" required />
                        </div>
                        <button type="submit">SUBMIT</button>
                    </form>
                </div>
            </div>

            <footer class="site-footer">
                <div class="footer-container">
                    <p>&copy; 2024 Telkom University.</p>
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