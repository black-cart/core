<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{bc_config_admin('ADMIN_TITLE')}} | {{ $title??'' }}</title>
        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('admin/black/') }}/img/apple-icon.png">
        <link rel="icon" type="image/png" href="{{ asset('admin/black/') }}/img/favicon.png">
        <!-- Fonts -->
        <link href="{{ asset('admin/black/fonts/Poppins-font.css') }}" rel="stylesheet" />
        <link href="{{ asset('admin/black/fonts/all.css') }}" rel="stylesheet" />
        <!-- Icons -->
        <link href="{{ asset('admin/black/') }}/css/nucleo-icons.css" rel="stylesheet" />
        <!-- CSS -->
        <link href="{{ asset('admin/black/') }}/css/white-dashboard.css?v=2.0.1" rel="stylesheet" />
        <link href="{{ asset('admin/black/') }}/css/theme.css" rel="stylesheet" />
    </head>
    <body class="{{ $class ?? '' }} overflow-hidden">
        @if ((Admin::isLoginPage() || Admin::isLogoutPage()))
            @include($templatePathAdmin.'Layout.header')
            <div class="wrapper wrapper-full-page">
                <div class="full-page login-page">
                    <div class="content">
                        @yield('main')
                    </div>
                    @section('block_footer')
                        @include($templatePathAdmin.'Layout.footer')
                    @show
                </div>
            </div>
        @else
            <div class="wrapper">
                    {{-- @include($templatePathAdmin.'sidebar') --}}
                @include($templatePathAdmin.'Layout.sidebar')
                <div class="main-panel">
                    @include($templatePathAdmin.'Layout.header')
                    <div class="content">
                        @yield('main')
                    </div>

                    @section('block_footer')
                        @include($templatePathAdmin.'Layout.footer')
                    @show
                    <div id="loading" style="display: none;">
                        <div id="overlay" class="overlay"><i class="fa fa-spinner fa-pulse fa-5x fa-fw "></i></div>
                    </div>
                </div>
            </div>
        @endif
        <div class="fixed-plugin">
            <div class="dropdown show-dropdown">
                <a href="#" data-toggle="dropdown">
                <i class="fa fa-cog fa-2x"> </i>
                </a>
                <ul class="dropdown-menu">
                    <li class="header-title">
                      Theme
                    </li>
                    <li class="adjustments-line">
                        <div class="togglebutton switch-change-theme">
                            <span class="label-switch">{{ ucfirst(config('admin.theme')[0]) }}</span>
                            <input type="checkbox" name="checkbox" class="bootstrap-switch">
                            <span class="label-switch label-right">{{ ucfirst(config('admin.theme')[1]) }}</span>
                        </div>
                    </li>
                    <li class="header-title"> Sidebar Background</li>
                    <li class="adjustments-line">
                      <a href="javascript:void(0)" class="switch-trigger background-color">
                        <div class="badge-colors text-center">
                          <span class="badge filter badge-primary" data-color="primary"></span>
                          <span class="badge filter badge-info" data-color="blue"></span>
                          <span class="badge filter badge-success" data-color="green"></span>
                          <span class="badge filter badge-warning" data-color="orange"></span>
                          <span class="badge filter badge-danger active" data-color="red"></span>
                        </div>
                        <div class="clearfix"></div>
                      </a>
                    </li>
                    <li class="header-title">
                      Sidebar Mini
                    </li>
                    <li class="adjustments-line">
                        <div class="togglebutton switch-sidebar-mini">
                            <span class="label-switch">OFF</span>
                            <input type="checkbox" name="checkbox" class="bootstrap-switch">
                            <span class="label-switch label-right">ON</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <script src="{{ asset('admin/black/') }}/js/core/jquery.min.js"></script>
        <script src="{{ asset('admin/black/') }}/js/core/popper.min.js"></script>
        <script src="{{ asset('admin/black/') }}/js/core/bootstrap.min.js"></script>
        <script src="{{ asset('admin/black/') }}/js/plugins/perfect-scrollbar.jquery.min.js"></script>
        <!--  Google Maps Plugin    -->
        <!-- Place this tag in your head or just before your close body tag. -->
        {{-- <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script> --}}
        <!-- Chart JS -->
        {{-- <script src="{{ asset('admin/black/') }}/js/plugins/chartjs.min.js"></script> --}}
        <!--  Notifications Plugin    -->
        <script src="{{ asset('admin/black/') }}/js/plugins/bootstrap-notify.js"></script>
        <script src="{{ asset('admin/black/') }}/js/plugins/sweetalert2.min.js"></script>
        <script src="{{ asset('admin/black/') }}/js/black-dashboard.min.js?t=27042021"></script>
        <script src="{{ asset('admin/black/') }}/js/theme.js?t=2021290"></script>
        <script src="{{ asset('admin/black/') }}/js/settings.js?t=2021217"></script>
        <script src="{{ asset('admin/black/') }}/js/plugins/bootstrap-switch.js"></script>
        <script src="{{ asset('admin/black/') }}/js/plugins/jquery.dataTables.min.js"></script>
        <script src="{{ asset('admin/black/') }}/js/plugins/jasny-bootstrap.min.js"></script>
        <script src="{{ asset('admin/black/') }}/js/plugins/bootstrap-selectpicker.js"></script>
        <script src="{{ asset('admin/black/') }}/js/plugins/bootstrap-tagsinput.js"></script>
        @if(substr(Route::currentRouteAction(), (strpos(Route::currentRouteAction(), '@') + 1) ) == 'index')            
          @include($templatePathAdmin.'Component.list_script')
        @endif
        @stack('scripts')
        @stack('css')

		@section('block_Component_script')
		  @include($templatePathAdmin.'Component.script')
		@show
		@section('block_Component_alerts')
		  @include($templatePathAdmin.'Component.alerts')
		@show
    </body>
</html>
