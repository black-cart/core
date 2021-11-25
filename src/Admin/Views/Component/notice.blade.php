
@php
    $orderNew = \BlackCart\Core\Admin\Models\AdminOrder::getCountOrderNew()
@endphp
<li class="dropdown nav-item">
    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
        <div class="notification d-none d-lg-block d-xl-block"></div>
        <i class="tim-icons icon-bell-55"></i>
        <p class="d-lg-none"> {{ __('Notifications') }} </p>
    </a>
    <ul class="dropdown-menu dropdown-menu-right dropdown-navbar">
        <li class="nav-link">
            <a href="{{ bc_route_admin('admin_order.index') }}?order_status=1" class="nav-item dropdown-item">{{ trans('admin.menu_notice.new_order',['total'=> $orderNew]) }}</a>
        </li>
    </ul>
</li>
