<div class="card border-0 shadow-lg mt-1">
    <div class="card-header  text-white">
        Welcome, {{Auth::user()->name }}
    </div>
    <div class="card-body sidebar">
        <ul class="nav flex-column">

            <li class="nav-item">
                <a href="{{route('account.dashboard')}}">Dashboard</a>
            </li>

            <li class="nav-item">
                <a href="{{route('account.profile')}}">Profile</a></a>
            </li>
            <li class="nav-item">
                <a href="{{route('account.logout')}}">Logout</a>
            </li>
        </ul>
    </div>
</div>