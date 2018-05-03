<!-- Top Navigation Bar -->
<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark" style="background-color:#003366 !important">
    <a class="navbar-brand" href="{{ url('/') }}">MINIMAINTENANCE</a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#toggleMenu" aria-controls="toggleMenu" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<!-- Responsive Drop Down Menu -->
<div id="toggleMenu" class="collapse navbar-collapse">
    <ul class="nav navbar-nav ml-auto">
         <!-- Authentication Links -->
         @guest
            <li class="nav-item">
                <a class="nav-link topNav-item" href="{{ url('/') }}">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link topNav-item" href="{{route('login')}}">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link topNav-item" href="{{route('register')}}">Register</a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link topNav-item" href="#">About</a>
            </li> --}}
        @else
            <li class="nav-item">
                <span class="navbar-text" id="welcomeMsg"><i class="fas fa-user-circle"></i> Welcome {{ Auth::user()->name }}</span>
            </li>
            @if(request()->path() == 'expenses' || request()->path() == 'fuel')
            <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle topNav-item" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Graph Data
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/{{request()->path()}}">Weekly</a>
                        <a class="dropdown-item" href="/{{request()->path()}}?timeSpan=monthly">Monthly</a>
                        <a class="dropdown-item" href="/{{request()->path()}}?timeSpan=yearly">Yearly</a>
                    </div>
                </li>
            @endif
            <li class="nav-item">
            <a class="nav-link topNav-item" href="{{route('logout')}}">Logout</a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link topNav-item" href="#">Help</a>
            </li> --}}
         @endguest  
    </ul>
        @guest
        @else
            <ul class="nav navbar-nav d-lg-none">
                <li class="nav-item"><a href="/dashboard" class="nav-link topNav-item">Dashboard</a></li>
                <li class="nav-item"><a href="/todo" class="nav-link topNav-item">Todo List</a></li>
                <li class="nav-item"><a href="/serviceHistory?personal=y&garage=y&specialist=y" class="nav-link topNav-item">Service History</a></li>
                <li class="nav-item"><a href="/expenses" class="nav-link topNav-item">Expenses</a></li>
                <li class="nav-item"><a href="/fuel" class="nav-link topNav-item">Fuel</a></li>
                {{-- <li class="nav-item"><a href="/findagarage" class="nav-link topNav-item">Find a Garage</a></li>
                <li class="nav-item"><a href="/events" class="nav-link topNav-item">Events</a></li>
                <li class="nav-item"><a href="/settings" class="nav-link topNav-item">Settings</a></li> --}}
            </ul>
        @endguest
</div>
</nav>