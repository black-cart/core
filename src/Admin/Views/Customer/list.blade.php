@extends($templatePathAdmin.'Layout.main')

@section('main')
<div class="row">
  {{-- top --}}
<div class="col-md-12">
  <div class="card" >
    <div class="card-header">
        <h4 class="card-title m-0">Tìm kiếm khách hàng</h4>
    </div>
    <div class="card-body" >
      <form action="{{bc_route_admin('admin_customer.index')}}" id="button_search">
        <div class="row justify-content-between">
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
            <div class="col-sm-7 d-flex justify-content-end">
                <div class="form-group d-flex">
                  <input type="text" name="keyword" class="form-control mr-3" value="{{$keyword}}" placeholder="{{ trans('customer.admin.search_place') }}">
                  <button type="submit" class="btn m-0 btn-success"><i class="fas fa-search"></i></button>
                </div>
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
                    <a class="dropdown-item" id="button_create_new" href="{{bc_route_admin('admin_customer.create')}}">{{trans('admin.add_new')}}</a>
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
        <div class="table-responsive">
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
                  <th >{{trans('customer.email')}}</th>
                  <th >{{trans('customer.name')}}</th>
                  <th >{{trans('customer.phone')}}</th>
                  <th >{{trans('customer.address1')}}</th>
                  <th >{{trans('customer.address2')}}</th>
                  <th >{{trans('customer.address3')}}</th>
                  <th >{{trans('customer.country')}}</th>
                  <th >{{trans('customer.status')}}</th>
                  <th >{{trans('customer.created_at')}}</th>
                  <th>{{trans('customer.admin.action')}}</th>
               </tr>
            </thead>
            <tbody>
              @foreach ($dataCustomer as $keyRow => $row)
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
                  <td >{{$row['email']}}</td>
                  <td >{{$row['name']}}</td>
                  <td >{{$row['phone']}}</td>
                  <td >{{$row['address1']}}</td>
                  <td >{{$row['address2']}}</td>
                  <td >{{$row['address3']}}</td>
                  <td >{{$country[$row['country']]->name}}</td>
                  <td >{!! $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>' !!}</td>
                  <td >{{$row['created_at']}}</td>
                  <td >
                    @include($templatePathAdmin.'Component.action_list',['url_edit'=> bc_route_admin('admin_customer.edit',['id'=>$row['id']]),'id'=>$row['id']])
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
{!! $js ?? '' !!}
@endpush
