<aside class="sidebar">
    <a class="sidebar-logo" href="{{ route('user.search', ['q' => session('motor_id')]) }}">
        <img src="/img/logo.png" alt="Logo" />
    </a>
    <div class="sidebar-menu">
        <ul>
            <li>
                <a href="/account" id="user">
                    <div class="menu-item">
                        <i data-feather="user"></i>
                        <span style="font-weight: bold">Account</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="/monitoruser" id="monitor">
                    <div class="menu-item">
                        <i data-feather="monitor"></i>
                        <span style="font-weight: bold">Monitoring <br>& Tracking</br></span>
                    </div>
                </a>
            </li>
            <li>
                <a href="/data" id="data">
                    <div class="menu-item">
                        <i data-feather="folder"></i>
                        <span style="font-weight: bold">Data</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="/informatiion" id="information">
                    <div class="menu-item">
                        <i data-feather="info"></i>
                        <span style="font-weight: bold">Information</br></span>
                    </div>
                </a>
            </li>
            <li style="text-align: center;">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline-block;">
                    @csrf
                    <button type="submit"
                        style="background: none; border: none; padding: 0; color: inherit; text-decoration: inherit; cursor: pointer;">
                        <a class="menu-item"
                            style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                            <i data-feather="log-out"></i>
                            <span style="font-weight: bold;">Logout</span>
                        </a>
                    </button>
                </form>
            </li>
            <li>
                <div class="menu-item search-bar">
                    <form action="{{ route('user.search') }}" method="get">
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