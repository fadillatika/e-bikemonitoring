     @extends('layouts.main')

     @section('container')
     <main class="content">
         <div class="header-content">
             <h1> Electrical Bike <span>Monitoring</span></h1>
             <img src="/img/motor.png" alt="icon motor" class="motor-icon">
         </div>
         <div class="hero-side">
             <p>Explore Our Electric Motor: 
                <br>Real-time Monitoring and Tracking for Optimal Performance!</br>
             </p>
             <div class="menu-item custom-search-bar">
                <form action="{{ route('search') }}" method="get">
                    <input type="text" name="q" placeholder="Search Your Motor ID Here..." />
                    <button type="submit">
                        <i data-feather="search"></i>
                    </button>
                </form>
            </div>            
         </div>
         <div class="collaboration-logo">
            <p>In Collaboration With:</p>
            <img src="/img/ptleniot.png" alt="Collaboration Logo">
        </div>
        <footer class="site-footer">
            <div class="footer-container">
              <p>&copy; 2024 Telkom University.</p>
            </div>
        </footer>          
     </main>
     @endsection