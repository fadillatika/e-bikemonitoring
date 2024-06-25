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

        <script src="https://unpkg.com/feather-icons"></script>

        <!-- More Icons -->
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        />
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css"
            integrity="sha384-wESLQ85D6gbsF459vf1CiZ2+rr+CsxRY0RpiF1tLlQpDnAgg6rwdsUF1+Ics2bni"
            crossorigin="anonymous"
        />

        <link rel="stylesheet" href="login.css" />

        <title>E-bike Monitoring!</title>
    </head>

    <body>
        <!-- Navbar start -->
        <nav class="navbar">
            <a class="navbar-logo" href="/home">
                <img src="img/logo.png" alt="Logo" width="135">
            </a>
            <div class="navbar-nav">
                <a href="/about"><i data-feather="users"></i>  About</a>
                <a href="/info" id="info"><i data-feather="info"></i>  Information</a>
            </div>
            <div class="navbar-extra">
                <a href="/login" id="login"><i data-feather="log-in"></i>  Login</a>
                <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
            </div>
        </nav>
        <!-- Hero Section -->
        <section
            class="hero"
            id="home"
            style="
                min-height: 115vh;
                display: flex;
                align-items: center;
                background-image: url('img/bg1.png');
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
                position: relative;
                text-align: center;
                overflow: hidden;
            "
        >
            <main class="content-row">
                <div class="header-content">
                    <h1> Electrical Bike <span>Monitoring</span></h1>
                    <img src="img/motor.png" alt="icon motor" class="motor-icon">
                </div>

                <div class="hero-side">
                    <div class="login-container">
                        <span class="title"> New Password</br> </br> </span>
                        <form action="{{ route('reset.passpost') }}" method="post">
                            @csrf
                            <input type="text" name="token" hidden value="{{ $token }}">
                            <div class="input-group">
                                <input type="email" name="email" placeholder="Enter your email" required />
                            </div>
                            <div class="input-group">
                                <input type="password" name="password" placeholder="password" required />
                            </div>
                            
                            <div class="input-group">
                                <input type="password" name="password_confirmation" placeholder="password_confirmation" required />
                            </div>

                            </br>
                            <button type="submit">CHANGED</button>
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
        <script src="login.js"></script>
    </body>
</html>