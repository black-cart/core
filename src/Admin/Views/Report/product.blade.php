@extends($templatePathAdmin.'Layout.main')

@section('main')
<div class="row">
  {{-- top --}}
<div class="col-md-12">
  <div class="card" >
    <div class="card-header">
        <h4 class="card-title m-0">Tìm kiếm sản phẩm</h4>
    </div>
    <div class="card-body" >
      <form action="{{bc_route_admin('admin_report.product')}}" id="button_search">
        <div class="row justify-content-between">
            @if (!empty($buttonSort))
            <div class="col-md-3">
              <div class="form-group mb-0">
                <select class="selectpicker mb-0" data-style="btn btn-primary" title="Select Sort order" tabindex="-98" id="sort_order" name="sort_order">
                  <option disabled selected>{{ trans('product.admin.sort') }}</option>
                    @foreach ($arrSort as $key => $sort) 
                      <option {{ (($sort_order == $key) ? "selected" : "") }} value="{{$key}}" >{!!$sort!!}</option>
                    @endforeach
                </select>
              </div>
            </div>
            @endif
            <div class="col-sm-7 d-flex justify-content-end">
                <div class="form-group d-flex">
                  <input type="text" name="keyword" class="form-control mr-3" value="{{$keyword}}" placeholder="{{ trans('product.admin.search_place') }}">
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
                  <th class="text-center">#</th>
                  <th>{{trans('product.image')}}</th>
                  <th class="text-center">{{trans('product.sku')}}</th>
                  <th class="text-center">{{trans('product.name')}}</th>
                  <th class="text-center">{{trans('product.price')}}</th>
                  <th class="text-center">{{trans('product.stock')}}</th>
                  <th class="text-center">{{trans('product.sold')}}</th>
                  <th class="text-center">{{trans('product.view')}}</th>
                  <th class="text-center">{{trans('product.kind')}}</th>
                  <th class="text-center">{{trans('product.status')}}</th>
               </tr>
            </thead>
            <tbody>
              @foreach ($dataReport as $keyRow => $row)
                @php
                  $kind_name = $kind = $kinds[$row['kind']] ?? $row['kind'];
                  $kind = '<span class="badge badge-info">'.$kind_name.'</span>';
                  if ($row['kind'] == BC_PRODUCT_BUILD) {
                      $kind = '<span class="badge badge-success">' . $kind_name . '</span>';
                  } elseif ($row['kind'] == BC_PRODUCT_GROUP) {
                      $kind = '<span class="badge badge-danger">' . $kind_name . '</span>';
                  }
                @endphp
              <tr>
                  <td class="text-center">{{$row['id']}}</td>
                  <td><img src="{{ bc_image_generate_thumb($row['image'],50,50) }}"></td>
                  <td class="text-center">{{$row['sku']}}</td>
                  <td class="">{{$row['name']}}</td>
                  <td class="text-center">{{$row['price']}}</td>
                  <td class="text-center">{{$row['stock']}}</td>
                  <td class="text-center">{{$row['sold']}}</td>
                  <td class="text-center">{{$row['view']}}</td>
                  <td class="text-center">{!! $kind !!}</td>
                  <td class="text-center">{!! $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>' !!}</td>
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
@include($templatePathAdmin.'Component.list_script')
{{-- //Pjax --}}
<script src="{{ asset('admin/plugin/jquery.pjax.js')}}"></script>
{{-- //End pjax --}}
{!! $js ?? '' !!}
@endpush
