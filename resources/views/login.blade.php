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

    <link rel="stylesheet" href="css/login.css" />

    <title>E-bike Monitoring!</title>
</head>

<body>
    <!-- Navbar start -->
    @include('partials.navbar2')
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
                    <span class="title"> Login Form</br></span>
                    <span class="note"> Please login first by entering your
                        e-Bike ID and password.</span> </br>
                    <div class="mt-5">
                        @if($errors->any())
                        <div class="col-12">
                            @foreach($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                            @endforeach
                        </div>
                        @endif

                        @if(session()->has('error'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                    </div>
                    <form action="{{ route('login') }}" method="post">
                        @csrf
                        <div class="input-group">
                            <i data-feather="user"></i>
                            <input type="text" name="username" placeholder="username" required />
                        </div>
                        <div class="input-group">
                            <i data-feather="lock"></i>
                            <input type="password" name="password" placeholder="password" required />
                        </div>
                        <div class="forget-pass">
                            <a href={{ route("forgot.password") }}>Forgot password?</a>
                        </div>
                        </br>
                        <button type="submit" style="font-family: 'Poppins', sans-serif;">LOGIN</button>
                    </form>
                </div>
            </div>
        </main>

        <footer class="site-footer">
            <div class="footer-container">
                <p>Copyright &copy;2024; Designed by Telkom University</p>
            </div>
        </footer>
    </section>
    <script>
        feather.replace();
    </script>

    <!-- Java script -->
    <script src="js/script.js"></script>
</body>

</html>