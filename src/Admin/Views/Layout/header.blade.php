@if ((Admin::isLoginPage() || Admin::isLogoutPage()))
<nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent fixed-top">
    <div class="container-fluid">
        <div class="navbar-wrapper">
            <div class="navbar-toggle d-inline">
                <button type="button" class="navbar-toggler">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </button>
            </div>
            <a class="navbar-brand" href="#">{{ $page ?? '' }}</a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
        </button>
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="{{ bc_route_admin('home') }}" class="nav-link text-primary">
                        <i class="tim-icons icon-minimal-left"></i> {{ __('Back to Dashboard') }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
@else
<nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
    <div class="container-fluid">
        <div class="navbar-wrapper">
            <div class="navbar-minimize d-inline">
                <button class="minimize-sidebar btn btn-link btn-just-icon" rel="tooltip" data-original-title="Sidebar toggle" data-placement="right" aria-describedby="tooltip393755">
                  <i class="tim-icons icon-align-center visible-on-sidebar-regular"></i>
                  <i class="tim-icons icon-bullet-list-67 visible-on-sidebar-mini"></i>
                </button>
            </div>
            <div class="navbar-toggle d-inline">
                <button type="button" class="navbar-toggler">
                  <span class="navbar-toggler-bar bar1"></span>
                  <span class="navbar-toggler-bar bar2"></span>
                  <span class="navbar-toggler-bar bar3"></span>
                </button>
            </div>
            <a class="navbar-brand" href="javascript:void(0)">{{ __('Dashboard') }}</a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
        </button>
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav ml-auto">
                
                @if (\Admin::user()->checkUrlAllowAccess(route('admin_order.index')))
                    <!-- SEARCH FORM -->
                    <li class="search-bar input-group">
                        <button class="btn btn-link" id="search-button" data-toggle="modal" data-target="#searchModal"><i class="tim-icons icon-zoom-split"></i>
                            <span class="d-lg-none d-md-block">{{ __('Search') }}</span>
                        </button>
                    </li>
                @endif
                @include($templatePathAdmin.'Component.notice')
                @include($templatePathAdmin.'Component.language')
                @include($templatePathAdmin.'Component.admin_profile')
                <li class="separator d-lg-none"></li>
            </ul>
        </div>
    </div>
</nav>

@if (\Admin::user()->checkUrlAllowAccess(route('admin_order.index')))
<div class="modal modal-search fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ bc_route_admin('admin_order.index') }}" method="get">
                <div class="modal-header">
                    <input name="keyword" type="text" class="form-control search-order" id="inlineFormInputGroup" placeholder="{{trans('order.search')}}">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                        <i class="tim-icons icon-simple-remove"></i>
                  </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endif
