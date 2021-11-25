@php
    $totalOrder = \BlackCart\Core\Admin\Models\AdminOrder::count();
    $styleStatus = \BlackCart\Core\Admin\Models\AdminOrder::$mapStyleStatus;
@endphp
@if ($totalOrder)
@php
    $arrStatus = \BlackCart\Core\Front\Models\ShopOrderStatus::pluck('name','id')->all();
    $groupOrder = (new \BlackCart\Core\Front\Models\ShopOrder)->all()->groupBy('status');
@endphp
<li class="">
    @foreach ($groupOrder as $status => $element)
    @php
        $percent = floor($element->count() * 100/$totalOrder);
    @endphp
    <a href="#">
        <div class="progress-container progress-{{ $styleStatus[$status] }}">
            <span class="progress-badge">Orders {{ $arrStatus[$status]??'' }}</span>
            <div class="progress">
                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $percent }}%;">
                    <span class="progress-value">{{ $percent }}%</span>
                </div>
            </div>
        </div>
    </a>
    @endforeach
</li>

@endif
