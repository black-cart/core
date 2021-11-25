@extends($templatePathAdmin.'Layout.main')



@section('main')
<div class="row">
    <div class="col-12">
        <div class="card card-chart">
            <div class="card-header ">
                <div class="row">
                    <div class="col-sm-6 text-left">
                        <h2 class="card-title ttl-chart">{{ trans('admin.order_year') }}</h2>
                    </div>
                    <div class="col-sm-6">
                        <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                        <label class="btn btn-sm btn-primary btn-simple active" id="0"
                            data-k="{{ json_encode(array_keys($orderInYear)) }}" 
                            dataod-v="{{ json_encode(array_values($orderInYear)) }}" 
                            datatt-v="{{ json_encode(array_values($amountInYear)) }}">
                            <input type="radio" class="d-none" name="options">
                            <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Year</span>
                            <span class="d-block d-sm-none">
                                <i class="tim-icons icon-tap-02"></i>
                            </span>
                        </label>
                        <label class="btn btn-sm btn-primary btn-simple" id="1"
                            data-k="{{ json_encode(array_keys($amountInMonth)) }}" 
                            datatt-v="{{ json_encode(array_values($amountInMonth)) }}"
                            dataod-v="{{ json_encode(array_values($orderInMonth)) }}">
                            <input type="radio" class="d-none d-sm-none" name="options">
                            <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Month</span>
                            <span class="d-block d-sm-none">
                                <i class="tim-icons icon-gift-2"></i>
                            </span>
                        </label>
                        <label class="btn btn-sm btn-primary btn-simple" id="2" 
                            data-k="{{ json_encode(array_keys($amountInWeek)) }}" 
                            datatt-v="{{ json_encode(array_values($amountInWeek)) }}"
                            dataod-v="{{ json_encode(array_values($orderInWeek)) }}">
                            <input type="radio" name="options" checked>
                            <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Week</span>
                            <span class="d-block d-sm-none">
                                <i class="tim-icons icon-single-02"></i>
                            </span>
                        </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="chartBig1">
                    </canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card card-stats">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="info-icon text-center icon-warning">
                            <i class="tim-icons icon-cart"></i>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="numbers">
                            <p class="card-category">{{ trans('admin.total_order') }}</p>
                            <h3 class="card-title">{{ number_format($totalOrder) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <hr>
                <div class="stats">
                    <a href="{{ bc_route_admin('admin_customer.index') }}"><i class="tim-icons icon-link-72"></i> {{ trans('admin.more') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card card-stats">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="info-icon text-center icon-primary">
                            <i class="tim-icons icon-tag"></i>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="numbers">
                            <p class="card-category">{{ trans('admin.total_product') }}</p>
                            <h3 class="card-title">{{ number_format($totalProduct) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <hr>
                <div class="stats">
                    <a href="{{ bc_route_admin('admin_customer.index') }}"><i class="tim-icons icon-link-72"></i> {{ trans('admin.more') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card card-stats">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="info-icon text-center icon-success">
                            <i class="tim-icons icon-single-02"></i>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="numbers">
                            <p class="card-category">{{ trans('admin.total_customer') }}</p>
                            <h3 class="card-title">{{ number_format($totalCustomer) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <hr>
                <div class="stats">
                    <a href="{{ bc_route_admin('admin_customer.index') }}"><i class="tim-icons icon-link-72"></i> {{ trans('admin.more') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card card-stats">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="info-icon text-center icon-danger">
                            <i class="tim-icons icon-molecule-40"></i>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="numbers">
                            <p class="card-category">{{ trans('admin.total_blogs') }}</p>
                            <h3 class="card-title">{{ number_format($totalNews) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <hr>
                <div class="stats">
                    <a href="{{ bc_route_admin('admin_customer.index') }}"><i class="tim-icons icon-link-72"></i> {{ trans('admin.more') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="card card-chart">
            <div class="card-header">
                <h5 class="card-category">Total Shipments</h5>
                <h3 class="card-title"><i class="tim-icons icon-bell-55 text-primary"></i> 763,215</h3>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="chartLinePurple"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card card-chart">
            <div class="card-header">
                <h5 class="card-category">Daily Sales</h5>
                <h3 class="card-title"><i class="tim-icons icon-delivery-fast text-info"></i> 3,500€</h3>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="CountryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card card-chart">
            <div class="card-header">
                <h5 class="card-category">Completed Tasks</h5>
                <h3 class="card-title"><i class="tim-icons icon-send text-success"></i> 12,100K</h3>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="chartLineGreen"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Global Sales by Top Locations</h4>
                <p class="card-category">All products that were shipped</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive ps">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th colspan="2">
                                            Country
                                        </th>
                                        <th>
                                            Amount
                                        </th>
                                        <th>
                                            Order
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(json_decode($dataCountries) as $row)
                                    <tr>
                                        <td>
                                            <div class="flag">
                                                <img src="https://flagcdn.com/24x18/{{strtolower($row->name)}}.png" alt="flag">
                                            </div>
                                        </td>
                                        <td>{{ $row->name }}</td>
                                        <td class="text-center">
                                            {{ number_format($row->amount).' .'.$row->currency }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->order }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto mr-auto">
                        <div id="worldMap" style="height: 300px;" data="{{ $dataCountries }}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-5">
        <div class="card card-tasks">
            <div class="card-header ">
                <h6 class="title d-inline">Tasks(5)</h6>
                <p class="card-category d-inline">today</p>
                <div class="dropdown">
                    <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown">
                        <i class="tim-icons icon-settings-gear-63"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="#pablo">Action</a>
                        <a class="dropdown-item" href="#pablo">Another action</a>
                        <a class="dropdown-item" href="#pablo">Something else</a>
                    </div>
                </div>
            </div>
            <div class="card-body ">
                <div class="table-full-width table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox" value="">
                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <p class="title">Update the Documentation</p>
                                    <p class="text-muted">Dwuamish Head, Seattle, WA 8:47 AM</p>
                                </td>
                                <td class="td-actions text-right">
                                    <button type="button" rel="tooltip" title="" class="btn btn-link" data-original-title="Edit Task">
                                        <i class="tim-icons icon-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox" value="" checked="">
                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <p class="title">GDPR Compliance</p>
                                    <p class="text-muted">The GDPR is a regulation that requires businesses to protect the personal data and privacy of Europe citizens for transactions that occur within EU member states.</p>
                                </td>
                                <td class="td-actions text-right">
                                    <button type="button" rel="tooltip" title="" class="btn btn-link" data-original-title="Edit Task">
                                        <i class="tim-icons icon-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox" value="">
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <p class="title">Solve the issues</p>
                                    <p class="text-muted">Fifty percent of all respondents said they would be more likely to shop at a company </p>
                                </td>
                                <td class="td-actions text-right">
                                    <button type="button" rel="tooltip" title="" class="btn btn-link" data-original-title="Edit Task">
                                        <i class="tim-icons icon-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox" value="">
                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <p class="title">Release v2.0.0</p>
                                    <p class="text-muted">Ra Ave SW, Seattle, WA 98116, SUA 11:19 AM</p>
                                </td>
                                <td class="td-actions text-right">
                                    <button type="button" rel="tooltip" title="" class="btn btn-link" data-original-title="Edit Task">
                                        <i class="tim-icons icon-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox" value="">
                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <p class="title">Export the processed files</p>
                                    <p class="text-muted">The report also shows that consumers will not easily forgive a company once a breach exposing their personal data occurs. </p>
                                </td>
                                <td class="td-actions text-right">
                                    <button type="button" rel="tooltip" title="" class="btn btn-link" data-original-title="Edit Task">
                                        <i class="tim-icons icon-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox" value="">
                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <p class="title">Arival at export process</p>
                                    <p class="text-muted">Capitol Hill, Seattle, WA 12:34 AM</p>
                                </td>
                                <td class="td-actions text-right">
                                    <button type="button" rel="tooltip" title="" class="btn btn-link" data-original-title="Edit Task">
                                        <i class="tim-icons icon-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <div class="tools float-right">
                    <div class="dropdown">
                        <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown">
                            <i class="tim-icons icon-settings-gear-63"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#pablo">Action</a>
                            <a class="dropdown-item" href="#pablo">Another action</a>
                            <a class="dropdown-item" href="#pablo">Something else</a>
                            <a class="dropdown-item text-danger" href="#pablo">Remove Data</a>
                        </div>
                    </div>
                </div>
                <h5 class="card-title">Management Table</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive ps">
                    <table class="table">
                        <thead class="text-primary">
                            <tr>
                                <th class="text-center">
                                    #
                                </th>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Job Position
                                </th>
                                <th>
                                    Milestone
                                </th>
                                <th class="text-right">
                                    Salary
                                </th>
                                <th class="text-right">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <div class="photo">
                                        <img src="https://black-dashboard-pro-laravel.creative-tim.com/black/img/tania.jpg" alt="photo">
                                    </div>
                                </td>
                                <td>
                                    Tania Mike
                                </td>
                                <td>
                                    Develop
                                </td>
                                <td class="text-center">
                                    <div class="progress-container progress-sm">
                                        <div class="progress">
                                            <span class="progress-value">25%</span>
                                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 25%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    € 99,225
                                </td>
                                <td class="text-right">
                                    <button type="button" rel="tooltip" class="btn btn-success btn-link btn-icon btn-sm btn-neutral" data-original-title="Refresh" title="">
                                        <i class="tim-icons icon-refresh-01"></i>
                                    </button>
                                    <button type="button" rel="tooltip" class="btn btn-danger btn-link btn-icon btn-sm btn-neutral" data-original-title="Delete" title="">
                                        <i class="tim-icons icon-simple-remove"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <div class="photo">
                                        <img src="https://black-dashboard-pro-laravel.creative-tim.com/black/img/robi.jpg" alt="photo">
                                    </div>
                                </td>
                                <td>
                                    John Doe
                                </td>
                                <td>
                                    CEO
                                </td>
                                <td class="text-center">
                                    <div class="progress-container progress-sm">
                                        <div class="progress">
                                            <span class="progress-value">77%</span>
                                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 77%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    € 89,241
                                </td>
                                <td class="text-right">
                                    <button type="button" rel="tooltip" class="btn btn-success btn-link btn-sm btn-icon btn-neutral" data-original-title="Refresh" title="">
                                        <i class="tim-icons icon-refresh-01"></i>
                                    </button>
                                    <button type="button" rel="tooltip" class="btn btn-danger btn-link btn-sm btn-icon btn-neutral" data-original-title="Delete" title="">
                                        <i class="tim-icons icon-simple-remove"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <div class="photo">
                                        <img src="https://black-dashboard-pro-laravel.creative-tim.com/black/img/lora.jpg" alt="photo">
                                    </div>
                                </td>
                                <td>
                                    Alexa Mike
                                </td>
                                <td>
                                    Design
                                </td>
                                <td class="text-center">
                                    <div class="progress-container progress-sm">
                                        <div class="progress">
                                            <span class="progress-value">41%</span>
                                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 41%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    € 92,144
                                </td>
                                <td class="text-right">
                                    <button type="button" rel="tooltip" class="btn btn-success btn-link btn-icon btn-sm btn-neutral" data-original-title="Refresh" title="">
                                        <i class="tim-icons icon-refresh-01"></i>
                                    </button>
                                    <button type="button" rel="tooltip" class="btn btn-danger btn-link btn-icon btn-sm btn-neutral" data-original-title="Delete" title="">
                                        <i class="tim-icons icon-simple-remove"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <div class="photo">
                                        <img src="https://black-dashboard-pro-laravel.creative-tim.com/black/img/jana.jpg" alt="photo">
                                    </div>
                                </td>
                                <td>
                                    Jana Monday
                                </td>
                                <td>
                                    Marketing
                                </td>
                                <td class="text-center">
                                    <div class="progress-container progress-sm">
                                        <div class="progress">
                                            <span class="progress-value">50%</span>
                                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 50%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    € 49,990
                                </td>
                                <td class="text-right">
                                    <button type="button" rel="tooltip" class="btn btn-success btn-link btn-sm btn-icon" data-original-title="Refresh" title="">
                                        <i class="tim-icons icon-refresh-01"></i>
                                    </button>
                                    <button type="button" rel="tooltip" class="btn btn-danger btn-link btn-sm btn-icon" data-original-title="Delete" title="">
                                        <i class="tim-icons icon-simple-remove"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <div class="photo">
                                        <img src="https://black-dashboard-pro-laravel.creative-tim.com/black/img/mike.jpg" alt="photo">
                                    </div>
                                </td>
                                <td>
                                    Paul Dickens
                                </td>
                                <td>
                                    Develop
                                </td>
                                <td class="text-center">
                                    <div class="progress-container progress-sm">
                                        <div class="progress">
                                            <span class="progress-value">100%</span>
                                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    € 69,201
                                </td>
                                <td class="text-right">
                                    <button type="button" rel="tooltip" class="btn btn-success btn-link btn-sm  btn-icon" data-original-title="Refresh" title="">
                                        <i class="tim-icons icon-refresh-01"></i>
                                    </button>
                                    <button type="button" rel="tooltip" class="btn btn-danger btn-link btn-sm btn-icon" data-original-title="Delete" title="">
                                        <i class="tim-icons icon-simple-remove"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <div class="photo">
                                        <img src="https://black-dashboard-pro-laravel.creative-tim.com/black/img/emilyz.jpg" alt="photo">
                                    </div>
                                </td>
                                <td>
                                    Manuela Rico
                                </td>
                                <td>
                                    Manager
                                </td>
                                <td class="text-center">
                                    <div class="progress-container progress-sm">
                                        <div class="progress">
                                            <span class="progress-value">15%</span>
                                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 15%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    € 99,201
                                </td>
                                <td class="text-right">
                                    <button type="button" rel="tooltip" class="btn btn-success btn-link btn-sm btn-icon" data-original-title="Refresh" title="">
                                        <i class="tim-icons icon-refresh-01"></i>
                                    </button>
                                    <button type="button" rel="tooltip" class="btn btn-danger btn-link btn-sm btn-icon" data-original-title="Delete" title="">
                                        <i class="tim-icons icon-simple-remove"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('admin/black/js/plugins/chartjs.min.js') }}"></script>
    <script src="{{ asset('admin/black/js/plugins/jquery-jvectormap.js') }}"></script>
    <script>
        $(document).ready(function() {
            theme.initVectorMap();
            theme.initDashboardPageCharts();
        });

    </script>
@endpush
