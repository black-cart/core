<li class="dropdown nav-item">
    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
        <div class="photo">
            <img src="{{ Admin::user()->avatar?asset(Admin::user()->avatar):asset('admin/avatar/user.jpg') }}" alt="{{ __('Profile Photo') }}">
        </div>
        <b class="caret d-none d-lg-block d-xl-block"></b>
        <p class="d-lg-none">{{ __('Log out') }}</p>
    </a>
    <ul class="dropdown-menu dropdown-navbar">
        <li class="nav-link">
            <a href="{{ bc_route_admin('admin.setting') }}" class="nav-item dropdown-item">{{ trans('admin.setting') }}</a>
        </li>
        <li class="dropdown-divider"></li>
        <li class="nav-link">
            <a href="{{ bc_route_admin('admin.logout') }}" class="nav-item dropdown-item">{{ trans('admin.logout') }}</a>
        </li>
    </ul>
</li>