@php
    $languages     = bc_language_all();
@endphp
<li class="dropdown nav-item">
    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
        <div class="photo overflow-visible">
            <img src="{{ asset($languages[session('locale')??app()->getLocale()]['icon']) }}">
            <b class="caret d-none d-lg-block d-xl-block"></b>
        </div>
    </a>
    <ul class="dropdown-menu dropdown-navbar">
        @foreach ($languages as $key=> $language)
            <li class="nav-link {{ ((session('locale')??app()->getLocale()) == $key)?' disabled':'' }}">
                <a href="{{ bc_route_admin('admin.locale', ['code' => $key]) }}" class="nav-item dropdown-item">
                    <img src="{{ asset($language['icon']) }}" style="height: 25px;"> {{ $language['name'] }}
                </a>
            </li>
        @endforeach
    </ul>
</li>