        <!-- Sidebar start -->
        <aside class="sidebar">       
            <a class="sidebar-logo" href="/home">
                <img src="/img/logo.png" alt="Logo" />
            </a>
            <div class="sidebar-menu">
                <ul>
                    <li>
                        <a href="/about" id="about">
                            <div class="menu-item">
                                <i data-feather="users"></i>
                                <span style="font-weight: bold">About</span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="/" id="home">
                            <div class="menu-item">
                                <i data-feather="home"></i>
                                <span style="font-weight: bold">Home Page</span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="/dashboard" id="grid">
                            <div class="menu-item">
                                <i data-feather="grid"></i>
                                <span style="font-weight: bold">Dashboard</span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="menu-item search-bar">
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
                        </div>
                    </li>
                </ul>
            </div>
        </aside>