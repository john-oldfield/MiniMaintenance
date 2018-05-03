@include('inc.header')
<div class="container-fluid">
    <div class="row">
        <!-- Side Navigation Bar -->
        <div class="d-none d-lg-block col-lg-1 sideNav fixed-top">
            <ul class="nav flex-column" id="sideNavMenu">
            <li class="nav-item {{Request::is('dashboard') ? "active" : ""}}">
                    <a class="nav-link" href="/dashboard">
                        <p><i class="fas fa-tachometer-alt side-icon"></i></p>
                        <p class="sideMenuText">Dashboard</p>
                    </a>
                </li>
                <li class="nav-item {{Request::is('todo') ? "active" : ""}}">
                    <a class="nav-link" href="/todo">
                        <p><i class="fas fa-list-ul side-icon"></i></p>
                        <p class="sideMenuText">Todo List</p>
                    </a>
                </li>
                <li class="nav-item {{Request::is('serviceHistory') ? "active" : ""}}">
                    <!-- href been bodged for time being -->
                    <a class="nav-link" href="/serviceHistory?personal=y&garage=y&specialist=y">
                        <p><i class="fas fa-history side-icon"></i></p>
                        <p class="sideMenuText">Service History</p>
                    </a>
                </li>
                <li class="nav-item {{Request::is('expenses') ? "active" : ""}}">
                    <a class="nav-link" href="/expenses">
                        <p><i class="fas fa-money-bill-alt side-icon"></i></p>
                        <p class="sideMenuText">Expenses</p>
                    </a>
                </li>
                <li class="nav-item {{Request::is('fuel') ? "active" : ""}}">
                    <a class="nav-link" href="/fuel">
                        <p><i class="fas fa-car side-icon"></i></p>
                        <p class="sideMenuText">Fuel</p>
                    </a>
                </li>
                {{-- <li class="nav-item {{Request::is('findagarage') ? "active" : ""}}">
                    <a class="nav-link" href="/findagarage">
                        <p><i class="fas fa-search side-icon"></i></p>
                        <p class="sideMenuText">Find a Garage</p>
                    </a>
                </li>
                <li class="nav-item {{Request::is('events') ? "active" : ""}}">
                    <a class="nav-link" href="/events">
                        <p><i class="fas fa-calendar-alt side-icon"></i></p>
                        <p class="sideMenuText">Events</p>
                    </a>
                </li>
                <li class="nav-item {{Request::is('settings') ? "active" : ""}}">
                    <a class="nav-link" href="/settings">
                        <p><i class="fas fa-cog side-icon"></i></p>
                        <p class="sideMenuText">Settings</p>
                    </a>
                </li> --}}
            </ul>
        </div>
        <!-- Main Content -->
        <div class="col-12 col-lg-11 main offset-lg-1">
            @include('inc.messages')
            @yield('content')
        </div>
    </div>
</div>