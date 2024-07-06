<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon" />

    <!-- PWA  -->
    <meta name="theme-color" content="#6777ef" />
    <link rel="apple-touch-icon" href="{{ asset('ebike-02.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    <!-- font -->
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Racing+Sans+One&display=swap" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />

    <!-- font ends here -->
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="css/about.css" />
    <link rel="stylesheet" href="css/custom.css" />
    <title>E-bike Monitoring!</title>
</head>

<body>
    <!-- Navbar start -->
    @include('partials.navbar2')
    <section class="first">
        <div class="container">
            <h1>About us</h1>
            <br />
            <p>
                This website was created for
                <strong>monitoring and tracking</strong> electric
                motorbikes. The website was also created to make it easier
                for electric motorbike users
                <strong>to monitor and track</strong> remotely.
            </p>
        </div>
    </section>

    <section class="second">
        <div class="container">
            <div class="left-img">
                <img src="img/track.jpg" alt="track" />
            </div>
            <div class="right-content">
                <h2>
                    What is electrical motorbike monitoring and tracking?
                </h2>
                <br />
                <br />
                <br />
                <br />
                <p>
                    Monitoring is useful for users to check the condition of
                    the motorbike remotely. Apart from that, there is also a
                    tracking feature that makes it easier for users to
                    maintain motorbike safety.
                </p>
            </div>
        </div>
    </section>

    <section class="third">
        <div class="container">
            <div class="right-img">
                <img src="img/Wavy_Tech-27_Single-11.jpg" alt="monitor" />
            </div>
            <div class="left-content">
                <h2>
                    What are the benefits of monitoring and tracking
                    electrical motorbikes?
                </h2>
                <br />
                <br />
                <br />
                <br />
                <p>
                    GPS Security Trackers can provide real-time location
                    data, enabling you to track the location of your
                    motorcycle from any device. Theft prevention: The
                    presence of a GPS tracker on your motorcycle can act as
                    a deterrent to potential thieves. Apart from that, it
                    can monitor the battery level on the electric motorbike
                    and lock the motorbike remotely.
                </p>
            </div>
        </div>
        <!-- .container -->
    </section>
    <!-- .third -->

    <section>
        <div class="row">
            <h1>Our Features</h1>
            <br />
        </div>

        <div class="row">
            <!-- Column One -->
            <div class="column">
                <div class="card">
                    <div class="icon">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                    <h3>Motorcycle tracking</h3>
                    <p>
                        This tracking feature serves as a real-time
                        monitoring of the existence of the motorbike so that
                        the existence of the motorbike can be known.
                    </p>
                </div>
            </div>

            <!-- Column Two -->
            <div class="column">
                <div class="card">
                    <div class="icon">
                        <i class="fa-solid fa-battery-half"></i>
                    </div>
                    <h3>Battery Level Monitoring</h3>
                    <p>
                        This feature functions as battery level monitoring.
                        So that users can find out the state of the battery
                        is exhausted or not.
                    </p>
                </div>
            </div>

            <!-- Column Three -->
            <div class="column">
                <div class="card">
                    <div class="icon">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <h3>Warning System</h3>
                    <p>
                        Warning system is a feature that serves as a warning
                        for motorcycle users who drive. A notification will
                        appear asking whether the motorbike is being driven
                        by the owner or not.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>Copyright &copy;2024; Designed by Telkom University</p>
        </div>
    </footer>

    <script src="{{ asset('/sw.js') }}"></script>
    <script>
        if ("serviceWorker" in navigator) {
            navigator.serviceWorker.register("/sw.js").then(
                (registration) => {
                    console.log("Service worker registration succeeded:", registration);
                },
                (error) => {
                    console.error(`Service worker registration failed: ${error}`);
                },
            );
        } else {
            console.error("Service workers are not supported.");
        }
    </script>

    <script>
        feather.replace();
    </script>

    <!-- Java script -->
    <script src="js/script.js"></script>
</body>

</html>