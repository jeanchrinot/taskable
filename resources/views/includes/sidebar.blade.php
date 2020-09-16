
        <aside class="sidebar">
            @php
                $nav_routes = [
                'dashboard'=> route('app.dashboard'),
                'users'=>route('app.users'),
                'profile'=>route('user.profile')
                ];

                $nav_routes = json_encode($nav_routes);
            @endphp
            <sidebar-nav routes="{{ $nav_routes }}"></sidebar-nav>
        </aside>