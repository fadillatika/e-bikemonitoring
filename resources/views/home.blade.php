@extends('layouts.main')

@section('container')
<main class="content">
    <div class="header-content">
        <h1> Electrical Bike <span>Monitoring</span></h1>
        <img src="/img/motor.png" alt="icon motor" class="motor-icon">
    </div>
    <div class="hero-side">
        <!-- <p>Explore Our Electric Motor With 
                <br>Real-time Monitoring and Tracking</br>
            </p> -->
        <div class="dropdown">
            <button class="dropbtn">Choose Motor ID</button>
            <div class="dropdown-content">
                <a href="/search?q=eb-01">EB-01</a>
                <a href="/search?q=eb-02">EB-02</a>
            </div>
        </div>
    </div>
    <footer class="site-footer">
        <div class="footer-container">
            <p>Copyright &copy;2024; Designed by Telkom University</p>
        </div>
    </footer>
</main>
@endsection