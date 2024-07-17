<!-- Sidebar start -->
<aside class="sidebar">
    <a class="sidebar-logo" href="/home">
        <img src="/img/logo.png" alt="Logo" />
    </a>
    <div class="sidebar-menu">
        <ul>
            <li>
                <a href="/login" id="login">
                    <div class="menu-item">
                        <i data-feather="log-in"></i>
                        <span style="font-weight: bold">Login</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="/about" id="about">
                    <div class="menu-item">
                        <i data-feather="users"></i>
                        <span style="font-weight: bold">About</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="/information" id="information">
                    <div class="menu-item">
                        <i data-feather="arrow-down-circle"></i>
                        <span style="font-weight: bold">Get The App</br></span>
                    </div>
                </a>
            </li>
            <li>
                <div class="menu-item search-bar">
                    <form action="{{ route('search') }}" method="get">
                        <input type="text" name="q" placeholder="Search ID" />
                        <button type="submit">
                            <i data-feather="search"></i>
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</aside>