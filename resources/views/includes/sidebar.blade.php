
        <aside class="sidebar">
            <nav class="sidebar__nav">
                <ul class="sidebar__menu">
                    <li class="title--small">
                        MAIN NAVIGATION
                    </li>
                    <li class="active">
                        <a href="{{ route('app.dashboard') }}" class="menu-toggle">
                            <i class="fa fa-bar-chart"></i>
                            <span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('app.tasks') }}">
                            <i class="fa fa-tasks"></i>
                            <span class="nav-label">Tasks</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.profile') }}">
                            <i class="fa fa-user"></i>
                            <span class="nav-label">Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('app.users') }}">
                            <i class="fa fa-users"></i>
                            <span class="nav-label">Users</span>
                        </a>
                    </li>
                    
                </ul>
            </nav>
        </aside>