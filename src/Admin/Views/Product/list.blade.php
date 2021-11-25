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
                <form action="{{bc_route_admin('admin_product.index')}}" id="button_search">
                    <input type="hidden" id="price_min_max" name="price_min_max">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" id="price_min" class="form-control" value="" placeholder="min">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" id="price_max" class="form-control" value="" placeholder="max">
                                    </div>
                                </div>
                                <div id="sliderDouble" class="slider slider-primary mt-3 noUi-target noUi-ltr noUi-horizontal"></div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <input type="text" name="keyword" class="form-control" value="{{$keyword}}" placeholder="{{ trans('product.admin.search_place') }}">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <div class="btn-group bootstrap-select col-sm-12 pl-0 pr-0">
                                    <select class="selectpicker col-sm-12 pl-0 pr-0" name="category_id" data-style="select-with-transition" title="" data-size="100" tabindex="-98">
                                        <option disabled selected>{{ trans('product.admin.select_category') }}</option>
                                        @foreach($categories as $k => $v)
                                            <option value="{{$k}}" {{($category_id == $k) ? 'selected' : ''}} >{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> 
                        @if($buttonSort)
                        <div class="col-sm-2">
                            <div class="form-group">
                                <div class="btn-group bootstrap-select col-sm-12 pl-0 pr-0">
                                    <select class="selectpicker col-sm-12 pl-0 pr-0" id="sort_order" name="sort_order" data-style="select-with-transition" data-size="100" tabindex="-98">
                                      <option disabled selected>{{ trans('product.admin.sort') }}</option>
                                      @foreach ($arrSort as $key => $status)
                                        <option {{ (($sort_order == $key) ? "selected" : "") }} value="{{$key}}">{!!$status!!}</option>
                                      @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-2 align-items-center d-flex justify-content-end align-self-start">
                            <button type="submit" class="btn btn-success m-0 w-100"><i class="fas fa-search"></i></button>
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
                            <a class="dropdown-item" id="button_create_new" href="{{bc_route_admin('admin_product.create')}}">{{trans('product.admin.add_new_title')}}</a>
                            @if(bc_config_admin('product_kind'))
                            <a href="{{bc_route_admin('admin_product.build_create')}}" class="dropdown-item" id="button_create_new">
                                {{trans('product.admin.add_new_title_build')}}
                            </a>
                            <a href="{{bc_route_admin('admin_product.group_create')}}" class="dropdown-item" id="button_create_new">
                                {{trans('product.admin.add_new_title_group')}}
                            </a>
                            @endif
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
                        <thead class="text-primary">
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
                                <th class="text-center">{{ trans('product.image') }}</th>
                                <th>{{ trans('product.sku') }}</th>
                                <th>{{ trans('product.name') }}</th>
                                <th class="text-center">{{ trans('product.category') }}</th>
                                @if (bc_config_admin('product_cost'))
                                <th class="text-center">{{ trans('product.cost') }}</th>
                                @endif
                                @if (bc_config_admin('product_price'))
                                <th class="text-center">{{ trans('product.price') }}</th>
                                @endif
                                @if (bc_config_admin('product_kind'))
                                <th class="text-center">{{ trans('product.kind') }}</th>
                                @endif
                                @if (bc_config_admin('product_property'))
                                <th class="text-center">{{ trans('product.property') }}</th>
                                @endif
                                <th class="text-center">{{ trans('product.status') }}</th>
                                <th class="text-center">{{ trans('product.admin.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataProds as $key => $row)
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
                                    <td><img src="{{ bc_image_generate_thumb($row->getThumb(),50,50) }}"></td>
                                    <td>{{ $row['sku'] }}</td>
                                    <td>{{ $row['name'] }}</td>
                                    <td>
                                        @foreach ($row->categories as $category)
                                            <span class="badge badge-danger">{{$categoriesTitle[$category->id]}}</span>
                                        @endforeach
                                    </td>
                                    @if(bc_config_admin('product_cost'))
                                        <td class="text-center">{{ bc_currency_render($row['cost'])}}</td>
                                    @endif
                                    @if(bc_config_admin('product_price'))
                                        <td class="text-center">{{ bc_currency_render($row['price'])}}</td>
                                    @endif
                                    @if(bc_config_admin('product_kind'))
                                        @php
                                            $kind = $kinds ? $kinds[$row['kind']] : $row['kind'];
                                            if ($row['kind'] == BC_PRODUCT_BUILD) {
                                                $kind = '<span class="badge badge-success">' . $kind . '</span>';
                                            } elseif ($row['kind'] == BC_PRODUCT_GROUP) {
                                                $kind = '<span class="badge badge-danger">' . $kind . '</span>';
                                            }else{
                                                $kind = '<span class="badge badge-info">' . $kind . '</span>';
                                            }
                                        @endphp
                                        <td class="text-center">{!! $kind !!}</td>
                                    @endif
                                    @if(bc_config_admin('product_property'))
                                        <td class="text-center">{{ $types ? $types[$row['property']] : $row['property']}}</td>
                                    @endif
                                    <td class="text-center">
                                        {!! $row['status'] ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-danger">OFF</span>' !!}
                                    </td>
                                    <td class="text-center">
                                    @include($templatePathAdmin.'Component.action_list',['url_edit'=> bc_route_admin('admin_product.edit',['id'=>$row['id']]),'id'=>$row['id']])
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
<script src="{{ asset('admin/black/js/plugins/nouislider.min.js') }}"></script>
<script src="{{ asset('admin/black/js/plugins/wNumb.js') }}"></script>
<script src="{{ asset('admin/plugin/jquery.pjax.js')}}"></script>
{{-- //End pjax --}}
<script type="text/javascript">
    theme.initSliders({{$price_min}},{{$price_max}},'sliderDouble');
</script>
{!! $js ?? '' !!}
@endpush