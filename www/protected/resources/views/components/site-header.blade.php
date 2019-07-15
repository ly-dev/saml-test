<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="brand" href="{{ url('/') }}">
                <img class="brand__logo" src="{{ asset('assets/images/logo.png') }}" alt="Logo"/>
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav navbar-left">
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li><a href="{{ url('auth/login') }}">Login</a></li>
                @else
                    @if (Auth::user()->isAdmin())
                    <li class="dropdown" id="admin-dropdown-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            Site Admin <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('auth/user') }}"><i class="fa fa-users" aria-hidden="true"></i>&nbsp;All Users</a></li>
                            <li><a href="{{ url('audit-log') }}"><i class="fa fa-binoculars" aria-hidden="true"></i>&nbsp;Audit Log</a></li>
                            <!-- li><a href="{{ url('tooltip') }}"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Tooltips</a></li -->
                            <li><a href="{{ url('taxonomy/variable') }}"><i class="fa fa-sliders" aria-hidden="true"></i>&nbsp;Taxonomy</a></li>
                            <li><a href="{{ url('basicpage') }}"><i class="fa fa-file" aria-hidden="true"></i>&nbsp;Basic Pages</a></li>
                        </ul>
                    </li>
                    @endif
                    <li class="dropdown" id="user-dropdown-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-user-o" aria-hidden="true"></i>&nbsp;{{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('auth/password/change') }}"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;Change Password</a></li>
                            <li><a href="{{ url('auth/logout') }}"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;Logout</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
