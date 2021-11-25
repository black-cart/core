@extends($templatePathAdmin.'Layout.main')

@section('main')
<div class="row">
  {{-- top --}}
<div class="col-md-12">
  <div class="card" >
    <div class="card-header">
        <h4 class="card-title m-0">Tìm kiếm đơn hàng</h4>
    </div>
    <div class="card-body" >
      <form action="{{bc_route_admin('admin_order.index')}}" id="button_search">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="text" name="keyword" class="form-control" value="{{$keyword}}" placeholder="{{ trans('order.admin.search_id') }}">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="text" class="form-control datetimepicker" name="from_to" value="" placeholder="From">
                </div>
            </div> 
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="text" class="form-control datetimepicker" name="end_to" value="" placeholder="To">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="text" name="email" class="form-control" value="{{$email}}" placeholder="{{ trans('order.admin.search_email') }}">
                </div>
            </div> 
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="text" name="name" class="form-control" value="{{$name}}" placeholder="{{ trans('admin.name') }}">
                </div>
            </div> 
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="text" name="phone" class="form-control" value="{{$phone}}" placeholder="{{ trans('admin.phone') }}">
                </div>
            </div> 
            <div class="col-sm-3">
                <div class="form-group">
                    <div class="btn-group bootstrap-select col-sm-12 pl-0 pr-0">
                        <select class="selectpicker col-sm-12 pl-0 pr-0" name="order_status" data-style="select-with-transition" title="" data-size="100" tabindex="-98">
                          <option disabled selected>{{ trans('order.admin.search_order_status') }}</option>
                          @foreach ($statusOrder as $key => $status) {
                            <option {{(($order_status == $key) ? "selected" : "") }} value="{{$key}}">{!!$status!!}</option>';
                          @endforeach
                        </select>
                    </div>
                </div>
            </div>
            @if (!empty($buttonSort))
              <div class="col-md-3">
                <div class="form-group mb-0">
                  <select class="selectpicker mb-0" data-style="btn btn-primary" title="Select Sort order" tabindex="-98" id="sort_order" name="sort_order">
                    <option disabled selected>{{ trans('product.admin.sort') }}</option>
                      @foreach ($arrSort as $key => $sort) {
                        <option {{ (($sort_order == $key) ? "selected" : "") }} value="{{$key}}" >{!!$sort!!}</option>
                      @endforeach
                  </select>
                </div>
              </div>
            @endif
            <div class="col-md-12 align-items-center d-flex justify-content-center">
                <button type="submit" class="btn btn-success"><i class="fas fa-search"></i></button>
            </div>
            
        </div>
      </form>
    </div>
  </div>
</div>
  <div class="col-md-12">
    <div class="card" >
      <div class="card-header">
        <div class="tools float-right">
            <div class="dropdown">
                <button type="button" class="btn btn-default dropdown-toggle btn-link btn-icon" data-toggle="dropdown">
                    <i class="tim-icons icon-settings-gear-63"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                    <a class="dropdown-item" id="button_create_new" href="{{bc_route_admin('admin_order.create')}}">{{trans('admin.add_new')}}</a>
                    @if (!empty($buttonRefresh))
                        <a class="dropdown-item grid-refresh" href="#">
                            {{ trans('admin.refresh') }}
                        </a>
                    @endif
                    @if (!empty($removeList))
                        <a class="dropdown-item text-danger grid-trash" href="#">{{ trans('admin.delete') }}</a>
                    @endif
                </div>
            </div>
        </div>
        <h4 class="card-title">{{ $title }}</h4>
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0" id="pjax-container">
        @php
            $urlSort = $urlSort ?? '';
        @endphp
        <div id="url-sort" data-urlsort="{!! strpos($urlSort, "?")?$urlSort."&":$urlSort."?" !!}"  style="display: none;"></div>
        <div class="table-responsive ps">
          <table class="table box-body">
            <thead>
              <tr>
                @if (!empty($removeList))
                <th class="text-center">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="form-check-input grid-row-checkbox grid-select-all" type="checkbox">
                      <span class="form-check-sign"></span>
                    </label>
                  </div>
                </th>
                @endif
                  <th>#</th>
                  <th>{{trans('order.admin.email')}}</th>
                  <th>{{trans('order.admin.subtotal')}}</th>
                  <th>{{trans('order.admin.shipping')}}</th>
                  <th>{{trans('order.admin.discount')}}</th>
                  <th>{{trans('order.admin.total')}}</th>
                  <th>{{trans('order.admin.payment_method_short')}}</th>
                  <th>{{trans('order.admin.currency')}}</th>
                  <th>{{trans('order.admin.status')}}</th>
                  <th>{{trans('order.admin.created_at')}}</th>
                  <th>{{trans('order.admin.action')}}</th>
               </tr>
            </thead>
            <tbody>
              @foreach ($dataOrd as $keyRow => $row)
              <tr>
                  @if (!empty($removeList))
                  <td class="text-center">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="form-check-input grid-row-checkbox" type="checkbox" data-id="{{ $row['id']??'' }}">
                        <span class="form-check-sign"></span>
                      </label>
                    </div>
                  </td>
                  @endif
                  <td>{{$row['id']}}</td>
                  <td>{{$row['email'] ?? 'N/A'}}</td>
                  <td>{{bc_currency_render_symbol($row['subtotal'] ?? 0, $row['currency'])}}</td>
                  <td>{{bc_currency_render_symbol($row['shipping'] ?? 0, $row['currency'])}}</td>
                  <td>{{bc_currency_render_symbol($row['discount'] ?? 0, $row['currency'])}}</td>
                  <td>{{bc_currency_render_symbol($row['total'] ?? 0, $row['currency'])}}</td>
                  <td>{{$row['payment_method']}}</td>
                  <td>{{$row['currency'] . '/' . $row['exchange_rate']}}</td>
                  <td>{{$statusOrder[$row['status']]}}</td>
                  <td>{{$row['created_at']}}</td>
                  <td>
                    @include($templatePathAdmin.'Component.action_list',['url_edit'=> bc_route_admin('admin_order.detail',['id'=>$row['id']]),'id'=>$row['id']])
                  </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        
        <div class="block-pagination clearfix m-10">
          <div class="ml-3 float-left">
            {!! $resultItems??'' !!}
          </div>
          <div class="pagination pagination-sm mr-3 float-right">
            {!! $pagination??'' !!}
          </div>
        </div>

      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
</div>
@endsection


@push('css')
<style type="text/css">
  .btn.dropdown-toggle[data-toggle=dropdown]{
    margin-bottom: 0px;
  }
  .pagination .page-item .page-link{
    line-height: 25px;
  }
</style>
{!! $css ?? '' !!}
@endpush

@push('scripts')
    {{-- //Pjax --}}
    <script src="{{ asset('admin/black/js/plugins/moment.min.js') }}"></script>
    <script src="{{ asset('admin/black/js/plugins/bootstrap-datetimepicker.js') }}"></script>
    <script src="{{ asset('admin/plugin/jquery.pjax.js')}}"></script>
    {{-- //End pjax --}}
  <script type="text/javascript">
    blackDashboard.initDateTimePicker();
  </script>
{!! $js ?? '' !!}
@endpush
