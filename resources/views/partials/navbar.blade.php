<nav class="navbar">
    <a class="navbar-logo" href="/home">
        <img src="/img/logo.png" alt="Logo" width="135">
    </a>
    <div class="navbar-nav">
        <a href="/about"><i data-feather="users"></i> About</a>
        <a href="/info" id="info"><i data-feather="info"></i> Information</a>
        <a href="/login" id="login"><i data-feather="log-in"></i> Login</a>
        <a class="menu-itemm search-barr">
            <form action="{{ route('search') }}" method="get">
                <input
                    type="text"
                    name="q"
                    placeholder="Search ID"
                />
                <button type="submit">
                    <i data-feather="search"></i>
                </button>
            </form>
        </a>
    </div>
    <div class="navbar-extra">
        <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
    </div>
</nav>