@extends($templatePathAdmin.'Layout.main')
@section('main')
<!-- form start -->
<form action="{{ bc_route_admin('admin_product.create') }}" method="post" name="form_name" accept-charset="UTF-8" class="form-horizontal" id="form-main" 
enctype="multipart/form-data">
<input type="hidden" name="kind" value="{{ $kind }}">
@csrf
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between pb-3 align-content-center">
                <h4 class="card-title align-self-center mb-0">{{ $title_description }}</h4>
                <a href="{{ bc_route_admin('admin_product.index') }}" class="btn btn-primary" title="List">
                    <i class="tim-icons icon-minimal-left"></i> 
                </a>
            </div>
        </div>
    </div>
    @foreach ($languages as $code => $language)
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $language->name }} {!! bc_image_render($language->icon,'20px','20px', $language->name) !!}</h4>
                </div>
                <div class="card-body">
                    <div class="form-group {{ $errors->has('descriptions.'.$code.'.name') ? ' text-red' : '' }}">
                        <label for="{{ $code }}__name">{{ trans('product.name') }}</label>
                        <input type="text" id="{{ $code }}__name" name="descriptions[{{ $code }}][name]"
                            value="{!! old('descriptions.'.$code.'.name') !!}"
                            class="form-control {{ $code.'__name' }}" placeholder="{{ trans('admin.max_c',['max'=>200]) }}" />
                        @include($templatePathAdmin.'Component.feedback',['field'=>'descriptions.'.$code.'.name'])
                    </div>

                    <div class="form-group {{ $errors->has('descriptions.'.$code.'.keyword') ? ' text-red' : '' }}">
                        <h5 class="mb-1" for="{{ $code }}__keyword">{{ trans('product.keyword') }}</h5>
                        <input type="text" id="{{ $code }}__keyword"
                            name="descriptions[{{ $code }}][keyword]"
                            value="{!! old('descriptions.'.$code.'.keyword') !!}"
                            class="form-control tagsinput {{ $code.'__keyword' }}" placeholder="{{ trans('admin.max_c',['max'=>200]) }}" />
                        @include($templatePathAdmin.'Component.feedback',['field'=>'descriptions.'.$code.'.keyword'])
                    </div>

                    <div class="form-group {{ $errors->has('descriptions.'.$code.'.description') ? ' text-red' : '' }}">
                        <label for="{{ $code }}__description">{{ trans('product.description') }}</label>
                        <textarea id="{{ $code }}__description"
                                name="descriptions[{{ $code }}][description]"
                                class="form-control {{ $code.'__description' }}" placeholder="{{ trans('admin.max_c',['max'=>300]) }}">{{ old('descriptions.'.$code.'.description') }}
                        </textarea>
                        @include($templatePathAdmin.'Component.feedback',['field'=>'descriptions.'.$code.'.description'])
                    </div>
                    @if($kind == BC_PRODUCT_SINGLE || $kind == BC_PRODUCT_BUILD)
                    <div class="form-group kind {{ $errors->has('descriptions.'.$code.'.content') ? ' text-red' : '' }}">
                        <label for="{{ $code }}__content">{{ trans('product.content') }}</label>
                        <textarea id="{{ $code }}__content" class="editor"
                            name="descriptions[{{ $code }}][content]">
                            {!! old('descriptions.'.$code.'.content') !!}
                        </textarea>
                        @include($templatePathAdmin.'Component.feedback',['field'=>'descriptions.'.$code.'.content'])
                    </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="carrd-title">Thông tin Chung</h4>
            </div>
            <div class="card-body">
                {{-- Category --}}
                <div class="form-group kind  {{ $errors->has('category') ? ' text-red' : '' }}">
                    @php
                    $listCate = [];
                    if (is_array(old('category'))) {
                        foreach(old('category') as $value){
                            $listCate[] = (int)$value;
                        }
                    }
                    @endphp
                    <label for="category">{{ trans('product.admin.select_category') }}</label>
                    <select class="form-control input-sm category selectpicker" multiple="multiple"
                        data-placeholder="{{ trans('product.admin.select_category') }}"
                        name="category[]">
                        @foreach ($categories as $k => $v)
                            <option value="{{ $k }}"
                                {{ (count($listCate) && in_array($k, $listCate))?'selected':'' }}>{{ $v }}
                            </option>
                        @endforeach
                    </select>
                    @include($templatePathAdmin.'Component.feedback',['field'=>'category'])
                </div>
                {{-- //Category --}}
                {{-- Product Image --}}
                <div class="form-group kind  {{ $errors->has('image') ? ' text-red' : '' }}">
                    <label for="image">{{ trans('product.wrapp_image') }}</label>
                    {{-- <button type="button" id="add_sub_image" class="btn btn-sm btn-flat btn-success">
                        <i class="tim-icons icon-simple-add" aria-hidden="true"></i>
                    </button>   --}}
                    <div class="row" id="toolTipGenerator">
                        @php
                            $image = old('image');
                        @endphp
                        @if($kind == BC_PRODUCT_BUILD)
                            @if (old('hotSpots'))
                                <input id="old_hotSpots" type="hidden" value="{{ json_encode(old('hotSpots')) }}">
                            @endif
                            <div class="properties col-md-6">
                                <button class="btn btn-primary mb-2 lfm" type="button" data-input="image" data-preview="preview_image_spot" data-type="product">
                                    {{ trans('product.admin.choose_image') }} <i class="tim-icons icon-cloud-upload-94"></i>
                                </button>
                                <div class="form-config-product-build hidden">
                                    <div class="form-group">
                                        <div class="btn-group">
                                            <div  class="btn btn-primary" id="t_addSpot">Create Spot</div>
                                            <button type="button" class="btn btn-warning" id="t_deleteSpot">Delete Spot</button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="t_spotType">Spot Type</label>
                                        <select id="t_spotType" class="form-control selectpicker">
                                            <option value="circle">Circle</option>
                                            <option value="square">Square</option>
                                            <option value="circleOutline">Circle Outline</option>
                                            <option value="squareOutline">Square Outline</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="t_spotSize">Spot Size</label>
                                        <select id="t_spotSize" class="form-control selectpicker">
                                            <option value="small">Small</option>
                                            <option value="medium">Medium</option>
                                            <option value="large">Large</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="t_spotColor">Spot Color</label>
                                        <select id="t_spotColor" class="form-control selectpicker">
                                            <option value="white">White</option>
                                            <option value="black">Black</option>
                                            <option value="red">Red</option>
                                            <option value="green">Green</option>
                                            <option value="blue">Blue</option>
                                            <option value="purple">Purple</option>
                                            <option value="pink">Pink</option>
                                            <option value="orange">Orange</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="t_toolTipWidth">Tooltip Width</label> 
                                        <div class="d-flex align-items-center">
                                            <input class="form-control col-sm-7" type="text" id="t_toolTipWidth" value="200" />
                                            <code class="pl-3">px</code>
                                        </div>
                                    </div>
                                    <div class="form-check form-group">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox" checked id="t_toolTipWidthAuto">
                                            <span class="form-check-sign"></span>
                                            Auto
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label for="t_popupPosition">Tooltip Position</label>
                                        <select class="form-control selectpicker" id="t_popupPosition">
                                            <option value="left">Left</option>
                                            <option value="right">Right</option>
                                            <option value="top">Top</option>
                                            <option value="bottom">Bottom</option>
                                        </select>
                                    </div>
                                    <div class="form-check form-group">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox" checked id="t_toolTipVisible">
                                            <span class="form-check-sign"></span>
                                            Always Visible
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label for="t_content">Content (html)</label>
                                        <select class="form-control selectpicker" id="t_change_content_type">
                                            <option value="">Choose Type Content</option>
                                            <option value="product">Product</option>
                                            <option value="text">Text</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="preview_image_spot">                
                                    @if($image)                
                                        <img class="target" src="{{ asset($image) }}" />
                                    @endif
                                </div>
                                <input type="hidden" id="image" name="image" value="{!! old('image') !!}" class="image"/>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group content_type hidden" id="content_type_text">
                                    <label for="content_type_text">Insert Text</label>
                                    <textarea id="t_content_text" class="editor"></textarea>
                                </div>

                                <div class="form-group content_type hidden" id="content_type_product">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Product</label>
                                                <select class="selectpicker" id="productBuild" data-live-search="true"
                                                data-placeholder="{{trans('product.admin.select_product_in_group')}}">
                                                    <option value="">Choose Product</option>
                                                    @foreach ($listProductSingle as $k => $v) 
                                                        <option value="{{$k}}" data-image="{{ bc_image_generate_thumb($v['image'],200,200) }}" data-tokens="{{$v['name']}}" >
                                                            {{$v['name']}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    @include($templatePathAdmin.'Component.feedback',['field'=>'productBuild'])
                                </div>
                            </div>
                        @else
                            <div class="col-md-6">
                                <button class="btn btn-primary mb-2 lfm" type="button" data-input="image" data-preview="preview_image" data-type="product">
                                    {{ trans('product.admin.choose_image') }} <i class="tim-icons icon-cloud-upload-94"></i>
                                </button>
                                <div class="thumbnail">
                                    <div class="img_holder" id="preview_image" >
                                        @if($image)
                                            <img src="{{ asset($image) }}">
                                        @endif
                                    </div>
                                    <input type="hidden" id="image" name="image" value="{!! old('image') !!}" class="image"/>
                                </div>
                            </div>
                        @endif
                    </div>
                    @include($templatePathAdmin.'Component.feedback',['field'=>'image'])
                </div>
                @if($kind == BC_PRODUCT_SINGLE)
                    <div class="form-group kind  {{ $errors->has('sub_image') ? ' text-red' : '' }}">
                        <label for="image">{{ trans('product.sub_image') }}</label>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-primary mb-2 lfm" type="button" data-input="sub_image" data-preview="preview_sub_image" data-type="product">
                                    {{ trans('product.admin.choose_image') }} <i class="tim-icons icon-cloud-upload-94"></i>
                                </button>
                                <div class="form-check form-check-radio mb-2">
                                    <label class="form-check-label" for="radioPrimarydefault">
                                        <input class="form-check-input" type="radio" name="type_show_image_desc" id="radioPrimarydefault" 
                                        value="default" checked >
                                        <span class="form-check-sign"></span>
                                        Default
                                    </label>
                                    <label class="form-check-label" for="radioPrimarythreesixty">
                                        <input class="form-check-input" type="radio" name="type_show_image_desc" id="radioPrimarythreesixty" 
                                        value="threesixty" {{ (old('type_show_image_desc') == 'threesixty')?'checked':'' }}>
                                        <span class="form-check-sign"></span>
                                        360 degree
                                    </label>
                                </div>
                                <div class="thumbnail">
                                    <div class="img_holder" id="preview_sub_image" >
                                        @php
                                            $sub_image = explode(',', old('sub_image'));
                                            $sub_image = array_filter($sub_image);
                                        @endphp
                                        @if($sub_image)
                                            <div class="images-grid">
                                                @foreach($sub_image as $image)
                                                    <img src="{{ asset($image) }}">
                                                @endforeach
                                            </div>
                                        @else
                                        @endif
                                    </div>
                                    <input type="hidden" id="sub_image" name="sub_image" value="{!! old('sub_image') !!}" class="sub_image" />
                                </div>
                            </div>
                        </div>
                        @include($templatePathAdmin.'Component.feedback',['field'=>'sub_image'])
                    </div>
                @endif
                {{-- //Product Image --}}
                {{-- SkU --}}
                <div class="form-group kind  {{ $errors->has('sku') ? ' text-red' : '' }}">
                    <label for="sku">{{ trans('product.sku') }}</label>
                    <input type="text" id="sku" name="sku"
                            value="{!! old('sku')??'' !!}" class="form-control sku"/>
                    @include($templatePathAdmin.'Component.feedback',['field'=>'sku'])
                </div>
                {{-- //SKU --}}
                {{-- LAS --}}
                <div class="form-group kind  {{ $errors->has('alias') ? ' text-red' : '' }}">
                    <label for="alias">{!! trans('product.alias') !!}</label>
                    <input type="text"  id="alias" name="alias"
                        value="{!! old('alias')??'' !!}" class="form-control alias" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'alias'])
                </div>
                {{-- //LAS --}}
            @if($kind == BC_PRODUCT_SINGLE || $kind == BC_PRODUCT_BUILD)
                {{-- select brand --}}
                @if (bc_config_admin('product_brand'))
                    <div class="form-group kind {{ $errors->has('brand_id') ? ' text-red' : '' }}">
                        <label for="brand_id"><a target=_new href="{{ bc_route_admin('admin_brand.index') }}">{{ trans('product.brand') }}</a> </label>
                        <select class="form-control brand_id selectpicker" name="brand_id">
                            <option value="">Select Brand</option>
                            @foreach ($brands as $k => $v)
                                <option value="{{ $k }}" {{ (old('brand_id') ==$k) ? 'selected':'' }}>{{ $v->name }}
                            </option>
                            @endforeach
                        </select>
                        @include($templatePathAdmin.'Component.feedback',['field'=>'brand_id'])
                    </div>  
                @endif
                {{-- //select brand --}} 

                {{-- select supplier --}}
                @if (bc_config_admin('product_supplier'))
                    <div class="form-group kind {{ $errors->has('supplier_id') ? ' text-red' : '' }}">
                        <label for="supplier_id"><a target=_new href="{{ bc_route_admin('admin_supplier.index') }}">{{ trans('product.supplier') }}</a></label>
                        <select class="form-control supplier_id selectpicker"
                            name="supplier_id">
                            <option value="">Select Supplier</option>
                            @foreach ($suppliers as $k => $v)
                            <option value="{{ $k }}" {{ (old('supplier_id') ==$k) ? 'selected':'' }}>{{ $v->name }}
                            </option>
                            @endforeach
                        </select>
                        @include($templatePathAdmin.'Component.feedback',['field'=>'supplier_id'])
                    </div>
                @endif
                {{-- //select brand --}}   


                {{-- cost --}}
                @if (bc_config_admin('product_cost'))
                    <div class="form-group kind {{ $errors->has('cost') ? ' text-red' : '' }}">
                        <label for="cost">{{ trans('product.cost') }}</label>
                        <input type="number" id="cost" name="cost" value="{!! old('cost')??0 !!}" class="form-control cost" />
                        @include($templatePathAdmin.'Component.feedback',['field'=>'cost'])
                    </div>
                @endif
                {{-- //cost --}}

                {{-- price --}}
                @if (bc_config_admin('product_price'))
                    <div class="form-group kind {{ $errors->has('price') ? ' text-red' : '' }}">
                        <label for="price">{{ trans('product.price') }}</label>
                        <input type="number" id="price" name="price" value="{!! old('price')??0 !!}" class="form-control price" placeholder="" />
                        @include($templatePathAdmin.'Component.feedback',['field'=>'price'])
                    </div>
                @endif
                {{-- //price --}}

                {{-- select tax --}}
                @if (bc_config_admin('product_tax'))
                    <div class="form-group kind {{ $errors->has('tax_id') ? ' text-red' : '' }}">
                        <label for="tax_id"><a target=_new href="{{ bc_route_admin('admin_tax.index') }}">{{ trans('product.tax') }}</a></label>
                        <select class="form-control tax_id selectpicker" name="tax_id">
                            <option value="0" {{ (old('tax_id') == 0) ? 'selected':'' }}>
                                {{ trans('tax.admin.non_tax') }}
                            </option>
                            <option value="auto" {{ (old('tax_id') == 'auto') ? 'selected':'' }}>{{ trans('tax.admin.auto') }}</option>
                            @foreach ($taxs as $k => $v)
                                <option value="{{ $k }}" {{ (old('tax_id') ==$k) ? 'selected':'' }}>{{ $v->name }}
                            </option>
                            @endforeach
                        </select>
                        @include($templatePathAdmin.'Component.feedback',['field'=>'tax_id'])
                    </div>
                @endif
                {{-- //select tax --}}   

                {{-- price promotion --}}
                @if (bc_config_admin('product_promotion'))
                    <div class="form-group kind {{ $errors->has('price_promotion') ? ' text-red' : '' }}">
                        <div class="mb-3 justify-content-between d-flex align-content-center">
                            <label class="align-self-center" for="price">{{ trans('product.price_promotion') }}</label>
                            @if (!old('price_promotion'))
                                <button type="button" class="btn btn-sm btn-success add_product_promotion">
                                    <i class="tim-icons icon-simple-add" aria-hidden="true"></i>
                                </button>
                                @else
                                <button title="Remove" class="btn btn-sm btn-danger removePromotion">
                                    <i class="tim-icons icon-simple-remove"></i>
                                </button>
                            @endif
                        </div>
                        <div class="row pl-3 pr-3">
                            @if (old('price_promotion'))
                                <div class="price_promotion col-md-12">
                                    <div class="form-group">
                                        <input type="number" id="price_promotion" name="price_promotion" value="{!! old('price_promotion')??0 !!}" class="form-control price"/>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('product.price_promotion_start') }}</label>\
                                        <input type="text" id="price_promotion_start" autocomplete="false" 
                                                name="price_promotion_start"
                                                value="{!!old('price_promotion_start')!!}"
                                                class="form-control price_promotion_start datetimepicker"/>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ trans('product.price_promotion_end') }}</label>
                                        <input type="text" id="price_promotion_end" autocomplete="false" 
                                                name="price_promotion_end" value="{!!old('price_promotion_end')!!}"
                                                class="form-control price_promotion_end datetimepicker"/>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @include($templatePathAdmin.'Component.feedback',['field'=>'price_promotion'])
                    </div>
                @endif
                {{-- //price promotion --}}

                {{-- stock --}}
                @if (bc_config_admin('product_stock'))
                    <div class="form-group kind {{ $errors->has('stock') ? ' text-red' : '' }}">
                        <label for="stock">{{ trans('product.stock') }}</label>
                            <input type="number" id="stock" name="stock"
                                value="{!! old('stock')??0 !!}" class="form-control stock" />
                        @include($templatePathAdmin.'Component.feedback',['field'=>'stock'])
                    </div>
                @endif
                {{-- //stock --}}

                {{-- minimum --}}
                <div class="form-group {{ $errors->has('minimum') ? ' text-red' : '' }}">
                    <label for="minimum">{{ trans('product.minimum') }}</label>
                    <input type="number" id="minimum" name="minimum" value="{!! old('minimum')??0 !!}" class="form-control minimum" placeholder="{{ trans('product.minimum_help') }}" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'minimum'])
                </div>
                {{-- //minimum --}}

                {{-- date available --}}
                @if (bc_config_admin('product_available'))
                    <div class="form-group kind {{ $errors->has('date_available') ? ' text-red' : '' }}">
                        <label for="date_available">{{ trans('product.date_available') }}</label>
                            <input type="text" id="date_available" name="date_available"
                                    value="{!!old('date_available')!!}"
                                    class="form-control date_available datetimepicker"/>
                            @include($templatePathAdmin.'Component.feedback',['field'=>'date_available'])
                    </div>
                @endif
                {{-- //date available --}}
                @if($kind == BC_PRODUCT_SINGLE)
                    {{-- property --}}
                    @if (bc_config_admin('product_property'))
                        <div class="form-group kind {{ $errors->has('property') ? ' text-red' : '' }}">
                            <label for="property"><a target=_new href="{{ bc_route_admin('admin_product_property.index') }}">{{ trans('product.property') }}</a></label>
                            <input type="text" name="download_path" value="{{ old('download_path') }}" class="form-control" id="download_path"
                                placeholder="{{ trans('product.properties.download_path') }}" style="{{ (old('property') != BC_PROPERTY_DOWNLOAD) ? 'display:none':'' }}"  />
                            @foreach ( $properties as $key => $property)
                                <div class="form-check form-check-radio">
                                    <label class="form-check-label" for="radioPrimary{{ $key }}">
                                        <input class="form-check-input" type="radio" name="property" id="radioPrimary{{ $key }}" 
                                        value="{{ $key }}" {{ ((!old() && $key == BC_PROPERTY_PHYSICAL) || old('property') == $key)?'checked':'' }}>
                                        <span class="form-check-sign"></span>
                                        {{ $property }}
                                    </label>
                                </div>
                            @endforeach
                            @include($templatePathAdmin.'Component.feedback',['field'=>'property'])
                        </div>
                    @endif
                    {{-- //property --}}
                    {{-- Custom fields --}}
                    @if ($customFields)
                        <label><a target=_new href="{{ bc_route_admin('admin_custom_field.index') }}">{{ trans('custom_field.admin.title') }}</a></label>
                        @foreach ($customFields as $keyField => $field)
                            @php
                                $default  = json_decode($field->default, true)
                            @endphp
                            <div class="form-group kind {{ $errors->has('fields.'.$field->code) ? ' text-red' : '' }}">
                                <label for="{{ $field->code }}" >{{ bc_language_render($field->name) }}</label>
                                @if ($field->option == 'radio')
                                    @if ($default)
                                        @foreach ($default as $key => $name)
                                            <div class="form-check form-check-radio">
                                                <label class="form-check-label" for="{{ $keyField.'__'.$key }}">
                                                    <input class="form-check-input" type="radio" name="fields[{{ $field->code }}]" id="{{ $keyField.'__'.$key }}" 
                                                    value="{{ $key }}" {{ (old('fields.'.$field->code) == $key)?'checked':'' }}>
                                                    <span class="form-check-sign"></span>
                                                    {{ $name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif
                                @elseif($field->option == 'select')
                                    @if ($default)
                                        <select class="form-control selectpicker {{ $field->code }}" name="fields[{{ $field->code }}]">
                                        <option value=""></option>
                                            @foreach ($default as $key => $name)
                                                <option value="{{ $key }}" {{ (old('fields.'.$field->code) == $key) ? 'selected':'' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                @else
                                    <textarea  id="field_{{ $field->code }}" name="fields[{{ $field->code }}]"
                                    class="form-control {{ $field->code }}" placeholder="" >{{ old('fields.'.$field->code) }}
                                    </textarea>
                                @endif
                                @include($templatePathAdmin.'Component.feedback',['field'=>'fields.'.$field->code])
                            </div>
                        @endforeach
                    @endif
                    {{-- //Custom fields --}}
                @endif
            @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        {{-- <a target=_new href="{{ bc_route_admin('admin_attribute_group.index') }}">{{ trans('product.attribute') }}</a> --}}
        @if($kind == BC_PRODUCT_SINGLE)
            @if (bc_config_admin('product_attribute'))
                @if (!empty($attributeGroup))
                @php
                $dataAtt = old('attribute');
                @endphp
                    @foreach ($attributeGroup as $attGroupId => $att)
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ $att->name }}</h4>
                        </div>
                        <div class="card-body">
                            @if (!empty($dataAtt[$attGroupId]['name']))
                                @foreach ($dataAtt[$attGroupId]['name'] as $key => $attValue)
                                <div class="row align-items-center mb-2">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>{{ trans('product.admin.add_attribute_place') }}</label>
                                            <input type="text" name="attribute[{{$attGroupId}}][name][]" value="{{$attValue}}" class="form-control" 
                                            placeholder="{{trans('product.admin.add_attribute_place')}}" />
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>{{ trans('product.admin.add_price_place') }}</label>
                                            <input type="number" name="attribute[{{$attGroupId}}][add_price][]" value="{{$dataAtt[$attGroupId]['add_price'][$key]}}" 
                                            class="form-control" 
                                            placeholder="{{trans('product.admin.add_price_place')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-sm btn-danger removeAttribute"><i class="tim-icons icon-simple-remove"></i></button>
                                    </div>
                                    @if($att->picker)
                                        <div class="col-md-7 text-center">
                                            <button class="w-100 btn btn-primary mb-2 lfm" type="button" 
                                                data-input="attribute_image_{{$key}}" 
                                                data-idgr="preview-{{$key}}" 
                                                data-preview="preview_attribute_image_{{$key}}" 
                                                data-type="product">
                                                {{ trans('product.admin.choose_image') }} <i class="tim-icons icon-cloud-upload-94"></i>
                                            </button>
                                            <div class="form-check form-check-radio mb-2">
                                                <label class="form-check-label" for="radioPrimarydefault_{{$key}}">
                                                    <input class="form-check-input" type="radio" name="attribute[{{$attGroupId}}][type_show][{{$key}}]" id="radioPrimarydefault_{{$key}}" 
                                                    value="default" {{ ($dataAtt[$attGroupId]['type_show'][$key] == 'default')?'checked':'' }} >
                                                    <span class="form-check-sign"></span>
                                                    Default 
                                                </label>
                                                <label class="form-check-label" for="radioPrimarythreesixty_{{$key}}">
                                                    <input class="form-check-input" type="radio" name="attribute[{{$attGroupId}}][type_show][{{$key}}]" id="radioPrimarythreesixty_{{$key}}" 
                                                    value="threesixty" {{ ($dataAtt[$attGroupId]['type_show'][$key] == 'threesixty')?'checked':'' }}>
                                                    <span class="form-check-sign"></span>
                                                    360 degree
                                                </label>
                                            </div>
                                            <div class="thumbnail">
                                                <div class="img_holder color-thief d-flex" data-group="{{$key}}" id="preview_attribute_image_{{$key}}">
                                                    @if($dataAtt[$attGroupId]['images'][$key])
                                                        @php
                                                            $images = explode(',', $dataAtt[$attGroupId]['images'][$key]);
                                                            $wimg = 100 / count($images).'%';
                                                        @endphp
                                                        <div class="images-grid">
                                                            @foreach($images as $image)
                                                                    <img src="{{ asset($image) }}" style="width:{{$wimg}};height:100%;padding:2px;">
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                                <input type="hidden" id="attribute_image_{{$key}}" name="attribute[{{$attGroupId}}][images][]" value="{{$dataAtt[$attGroupId]['images'][$key]}}" class="att_image">
                                            </div>
                                        </div>
                                        <div class="col-md-5">                                        
                                            <div class="d-flex flex-wrap justify-content-between palette-{{$key}}">
                                                @if($dataAtt[$attGroupId]['code'][$key])
                                                    @php
                                                        $codes = explode(',', $dataAtt[$attGroupId]['code'][$key]);
                                                    @endphp
                                                    @foreach($codes as $code)
                                                        <div data-grid="{{$key}}" data-color="{{$code}}" class="box-clr-att active-att" style="background-color:{{$code}};"></div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <input type="hidden" name="attribute[{{$attGroupId}}][code][]" class="code-att-{{$key}}" value="{{$dataAtt[$attGroupId]['code'][$key]}}">
                                        </div>
                                    @endif
                                </div>
                                @endforeach
                            @endif
                            <button type="button"
                                class="btn btn-flat btn-success add_attribute"
                                data-picker="{{ $att->picker }}"
                                data-id="{{ $attGroupId }}">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                {{ trans('product.admin.add_attribute') }}
                            </button>
                        </div>
                    </div>
                    @endforeach
                @endif
            @endif
            {{-- //end List product build --}}
        @elseif($kind == BC_PRODUCT_GROUP)
            {{-- List product group --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ trans('product.admin.select_product_in_group') }}</h4>
                </div>
                <div class="card-body">
                    <div class="form-group kind  {{ $errors->has('productInGroup') ? ' text-red' : '' }}">
                        @php
                            $listgroups= old('productInGroup') ?: [];
                        @endphp
                        <div class="form-group">
                            <div class="btn-group w-80">
                                <select class="form-control productInGroup selectpicker" multiple="multiple" data-live-search="true"
                                data-placeholder="{{trans('product.admin.select_product_in_group')}}" name="productInGroup[]">
                                    @foreach ($listProductSingle as $k => $v) 
                                        <option {{in_array($k, $listgroups) ? 'selected' : ''}} value="{{$k}}">
                                            {{$v['name']}}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-danger m-0 removeproductInGroup">
                                    <i class="tim-icons icon-simple-remove"></i>
                                </button>
                            </div>
                        </div>
                        @include($templatePathAdmin.'Component.feedback',['field'=>'productInGroup'])
                    </div>
                </div>
            </div>
            {{-- //end List product group --}}
        @endif
        @if($kind == BC_PRODUCT_SINGLE || $kind == BC_PRODUCT_BUILD)
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Kích thước cân nặng</h4>
                </div>
                <div class="card-body">
                    @if (bc_config_admin('product_weight'))
                        {{-- weight --}}
                        <div class="form-group kind {{ $errors->has('weight_class') ? ' text-red' : '' }}">
                            <label for="weight_class"><a target=_new href="{{ bc_route_admin('admin_weight_unit.index') }}">{{ trans('product.weight_class') }}</a></label>
                            <select class="form-control weight_class selectpicker" name="weight_class">
                                <option value="">{{ trans('product.select_weight') }}</option>
                                @foreach ($listWeight as $k => $v)
                                    <option value="{{ $k }}" {{ (old('weight_class') == $k || (!old()) ) ? 'selected':'' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                            @include($templatePathAdmin.'Component.feedback',['field'=>'weight_class'])
                        </div>


                        <div class="form-group kind {{ $errors->has('weight') ? ' text-red' : '' }}">
                            <label for="weight" >{{ trans('product.weight') }}</label>
                            <input type="number" id="weight" name="weight" value="{!! old('weight', 0) !!}" class="form-control weight" placeholder="" />
                            @include($templatePathAdmin.'Component.feedback',['field'=>'weight'])
                        </div>
                        {{-- //weight --}}
                    @endif


                    @if (bc_config_admin('product_length'))
                        {{-- length --}}
                        <div class="form-group kind {{ $errors->has('length_class') ? ' text-red' : '' }}">
                            <label for="length_class" ><a target=_new href="{{ bc_route_admin('admin_length_unit.index') }}">{{ trans('product.length_class') }}</a></label>
                            <select class="form-control length_class selectpicker" name="length_class">
                                <option value="">{{ trans('product.select_length') }}<option>
                                @foreach ($listLength as $k => $v)
                                <option value="{{ $k }}"
                                    {{ (old('length_class') == $k || (!old()) ) ? 'selected':'' }}>
                                    {{ $v }}</option>
                                @endforeach
                            </select>
                            @include($templatePathAdmin.'Component.feedback',['field'=>'length_class'])
                        </div>

                        <div class="form-group kind {{ $errors->has('length') ? ' text-red' : '' }}">
                            <label for="length">{{ trans('product.length') }}</label>
                                <input type="number" id="length" name="length" value="{!! old('length', 0) !!}" class="form-control length" placeholder="" />
                            @include($templatePathAdmin.'Component.feedback',['field'=>'length'])
                        </div>

                        <div class="form-group kind   {{ $errors->has('height') ? ' text-red' : '' }}">
                            <label for="height" >{{ trans('product.height') }}</label>
                            <input type="number" id="height" name="height" value="{!! old('height', 0) !!}" class="form-control height" placeholder="" />
                            @include($templatePathAdmin.'Component.feedback',['field'=>'height'])
                        </div>

                        <div class="form-group kind {{ $errors->has('width') ? ' text-red' : '' }}">
                            <label for="width" >{{ trans('product.width') }}</label>
                            <input type="number" id="width" name="width" value="{!! old('width', 0) !!}" class="form-control width" placeholder="" />
                            @include($templatePathAdmin.'Component.feedback',['field'=>'width'])
                        </div>                        
                    {{-- //length --}}  
                    @endif
                </div>
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Hoàn Tất</h4>
            </div>
            <div class="card-body">
                {{-- sort --}}
                    <div class="form-group {{ $errors->has('sort') ? ' text-red' : '' }}">
                        <label for="sort">{{ trans('product.sort') }}</label>
                        <input type="number" id="sort" name="sort" value="{!! old('sort')??0 !!}" class="form-control sort" placeholder="" />
                        @include($templatePathAdmin.'Component.feedback',['field'=>'sort'])
                    </div>
                {{-- //sort --}}

                {{-- status --}}
                    <div class="form-group">
                        <p class="category">{{ trans('product.status') }}</p>
                        <input type="checkbox" {{((old('status') ==='on')?'checked':'')}} name="status" class="bootstrap-switch" data-on-label="ON" data-off-label="OFF">
                    </div>
                {{-- //status --}}
            </div>
            <div class="card-footer">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary submit">{{ trans('admin.submit') }}</button>
                    <button type="reset" class="btn btn-warning">{{ trans('admin.reset') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin/black/css/images-grid.css') }}">
    <style type="text/css">
        .active-att{
            border: 2px solid #fff;
            box-shadow: 0px 0px 4px 1px #fff;
        }
        .box-clr-att{
            width:35px;
            height:35px;
            border-radius: 50%;
            cursor:pointer;
            margin:3px;
        }
        .box-clr-att:hover{
            border: 2px solid #fff;
            box-shadow: 0px 0px 4px 1px #fff;
        }
        .imgs-grid-image .image-wrap img{
            width: unset!important;
        }
        .hidden{
            display: none;
        }
        .bs-searchbox .form-control,.bs-searchbox~.dropdown-menu .no-results{
            color: #292b2c;
        }
    </style>
@endpush

@push('scripts')
@if($kind == BC_PRODUCT_SINGLE || $kind == BC_PRODUCT_BUILD)
    @include($templatePathAdmin.'Component.ckeditor_js')
    <script type="text/javascript">
        $('textarea.editor').ckeditor(
            {
                filebrowserImageBrowseUrl: '{{ bc_route_admin('admin.home').'/'.config('lfm.url_prefix') }}?type=product',
                filebrowserImageUploadUrl: '{{ bc_route_admin('admin.home').'/'.config('lfm.url_prefix') }}/upload?type=product&_token={{csrf_token()}}',
                filebrowserBrowseUrl: '{{ bc_route_admin('admin.home').'/'.config('lfm.url_prefix') }}?type=Files',
                filebrowserUploadUrl: '{{ bc_route_admin('admin.home').'/'.config('lfm.url_prefix') }}/upload?type=file&_token={{csrf_token()}}',
                filebrowserWindowWidth: '900',
                filebrowserWindowHeight: '500'
            }
        );
    </script>
@endif
@if($kind == BC_PRODUCT_BUILD)
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/black/css/hotspot.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/black/css/jquery-ui.css')}}">
    <script src="{{ asset('admin/black/js/plugins/jquery-ui.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('admin/black/js/hotspot-config.js')}}"></script>
    <script type="text/javascript">
        $('body').on('change', '#preview_image_spot', function(event) {
            $('#toolTipGenerator').find('.form-config-product-build').removeClass('hidden');
        });
    </script>
@endif
<script src="{{ asset('admin/black/js/plugins/moment.min.js') }}"></script>
<script src="{{ asset('admin/black/js/plugins/bootstrap-datetimepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/black/js/plugins/color-thief.min.js') }}"></script>
<script src="{{ asset('admin/black/js/images-grid.js') }}"></script>
<script type="text/javascript">
blackDashboard.initDateTimePicker();
$('.submit').click(function(event) {
    $('#form-main').submit();
});
$("[name='property']").change(function() {
    if($(this).val() == '{{ BC_PROPERTY_DOWNLOAD }}') {
        $('#download_path').show();
    } else {
        $('#download_path').hide();
    }
});
// Select product in build
$('#add_product_in_build').click(function(event) {
    var $cloned = $(this).parents('.card').find('.row:first').clone();
    $cloned.find('.bootstrap-select').replaceWith(function() { return $('select', this); });
    $(this).parents('.card').children('.card-body').append($cloned);
    $cloned.find('.selectpicker').selectpicker('refresh');
});
$('body').on('click','.removeproductBuild',function(event) {
    if ($(this).parents('.card-body').find('.row').length < 2) {
        theme.showNotification('top','right','Không được xóa sản phẩm cuối !',3);
        return false;
    }
    $(this).closest('.row').remove();
});
//end select in build
// Promotion
$('body').on('click','.add_product_promotion',function(event) {
    $(this).parents('.kind').find('.row').append(
        '<div class="price_promotion col-md-12">'+
            '<div class="form-group">'+
                '<input type="number" id="price_promotion" name="price_promotion" value="" class="form-control price"/>'+
            '</div>'+
            '<div class="form-group">'+
                '<label>{{ trans('product.price_promotion_start') }}</label>'+
                '<input type="text" id="price_promotion_start"'+
                        'name="price_promotion_start"'+
                        'class="form-control price_promotion_start datetimepicker"/>'+
            '</div>'+
            '<div class="form-group">'+
                '<label>{{ trans('product.price_promotion_end') }}</label>'+
                '<input type="text" id="price_promotion_end"'+
                        'name="price_promotion_end" value="{!!old('price_promotion_end')!!}"'+
                        'class="form-control price_promotion_end datetimepicker"/>'+
            '</div>'+
        '</div>');
    $(this).removeClass('btn-success add_product_promotion').addClass('removePromotion btn-danger');
    $(this).children().removeClass('icon-simple-add').addClass('icon-simple-remove');
    $datetimepicker = $(".datetimepicker");
    blackDashboard.initDateTimePicker();
});
$('body').on('click','.removePromotion',function(event) {
    $(this).removeClass('btn-danger removePromotion').addClass('add_product_promotion btn-success');
    $(this).children().removeClass('icon-simple-remove').addClass('icon-simple-add');
    $(this).parents('.kind').find('.price_promotion').remove();
});
//End promotion

// Add sub images
{{-- // var id_sub_image = {{ old('sub_image')?count(old('sub_image')):0 }}; --}}
// $('#add_sub_image').click(function(event) {
//     id_sub_image +=1;
//     $(this).parents('.kind').find('.row').append(
//     '<div class="col-md-4 text-center">'+
//             '<div class="thumbnail lfm" data-input="sub_image_'+id_sub_image+'" data-preview="preview_sub_image_'+id_sub_image+'" data-type="product">'+
//                 '<div class="img_holder" id="preview_sub_image_'+id_sub_image+'" >'+
{{-- //                     '<img src="{{ asset('admin/black/img/image_placeholder.jpg') }}">'+ --}}
//                 '</div>'+
{{-- //                 '<input type="hidden" id="sub_image_'+id_sub_image+'" name="sub_image[]" value="{!! old('image') !!}"class="sub_image"/>'+ --}}
//                 '<button class="btn btn-sm btn-danger removeImage"><i class="tim-icons icon-simple-remove"></i></button>'+
//             '</div>'+
//         '</div>');
//     $('.removeImage').click(function(event) {
//         $(this).closest('div.col-md-4').remove();
//     });
//     $('.lfm').filemanager();
// });
// $('.removeImage').click(function(event) {
//     $(this).closest('.div.col-md-4').remove();
// });
//end sub images

// Select product attributes
$('body').on('click','.add_attribute',function(event) {
    var attGroup = $(this).data("id");
    var picker = $(this).data("picker");
    var htmlPickColorThef = '';
    if (picker) {
        var key = $(this).parents('.card-body').find('.row').length;
        htmlPickColorThef = '<div class="col-md-7">'+
                                    '<button class="w-100 btn btn-primary mb-2 lfm"'+
                                        'data-input="attribute_image_'+key+'"'+
                                        'data-idgr="preview-'+key+'"'+ 
                                        'data-preview="preview_attribute_image_'+key+'"'+
                                        'data-type="product">'+
                                            '{{ trans('product.admin.choose_image') }} <i class="tim-icons icon-cloud-upload-94"></i>'+
                                    '</button>'+
                                    '<div class="form-check form-check-radio mb-2">'+
                                        '<label class="form-check-label" for="radioPrimarydefault_'+key+'">'+
                                            '<input class="form-check-input" type="radio" name="attribute['+attGroup+'][type_show]['+key+']" id="radioPrimarydefault_'+key+'" '+
                                            'value="default" checked >'+
                                            '<span class="form-check-sign"></span>'+
                                            'Default '+
                                        '</label> '+
                                        ' <label class="form-check-label" for="radioPrimarythreesixty_'+key+'">'+
                                            '<input class="form-check-input" type="radio" name="attribute['+attGroup+'][type_show]['+key+']" id="radioPrimarythreesixty_'+key+'"'+ 
                                            'value="threesixty">'+
                                            '<span class="form-check-sign"></span>'+
                                            '360 degree'+
                                        '</label>'+
                                    '</div>'+
                                    '<div class="thumbnail mb-2">'+
                                        '<div class="img_holder pointer color-thief" data-group="'+key+'" id="preview_attribute_image_'+key+'">'+
                                        '</div>'+
                                        '<input type="hidden" id="attribute_image_'+key+'" name="attribute['+attGroup+'][images][]" value="" class="att_image">'+
                                    '</div>'+
                                '</div>'+
                                '<div class="col-md-5">'+
                                    '<div class="d-flex flex-wrap justify-content-between palette-'+key+'"></div>'+
                                    '<input type="hidden" name="attribute['+attGroup+'][code][]" class="code-att-'+key+'">'+
                                '</div>';
    }
    var htmlProductAtrribute = '<div class="row align-items-center">'+
                                    '<div class="col-md-5">'+
                                        '<div class="form-group">'+
                                            '<label>Nhập một thuộc tính</label>'+
                                            '<input type="text" name="attribute['+attGroup+'][name][]" value="" class="form-control" placeholder="Nhập một thuộc tính">'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="col-md-5">'+
                                        '<div class="form-group">'+
                                            '<label>Thêm tiền</label>'+
                                            '<input type="number" name="attribute['+attGroup+'][add_price][]" value="0" class="form-control" placeholder="Thêm tiền">'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="col-md-2">'+
                                        '<button type="button" class="btn btn-sm btn-danger removeAttribute"><i class="tim-icons icon-simple-remove"></i></button>'+
                                    '</div>'+
                                    htmlPickColorThef+
                                '</div>';
    $(this).before(htmlProductAtrribute);
    if (picker) {        
        $('.lfm').filemanager();
    }
});
$('body').on('click','.removeAttribute',function(event) {
    $(this).closest('.row').remove();
});

const rgbToHex = (r, g, b) => '#' + [r, g, b].map(x => {
    const hex = x.toString(16)
    return hex.length === 1 ? '0' + hex : hex
}).join('');
$('body').on('change','.color-thief',function(event) {
    const swatches = 9;
    const colorThief = new ColorThief();
    var image = new Image();
    var code = [];
    const grid = $(this).data('group');
    const palette = $(this).parents('.row').find('.palette-'+grid);
    const imgs = $(this).find('img');
    var   imgsleng = 4;
    image.src = imgs.attr('src');
    if (imgs.length < 4) {
        imgsleng = imgs.length;
    }
    $.each(imgs, function(index, val) {
        $(val).css({
            height: '100%',
            width: 100 / imgsleng+'%',
            padding: '2px 2px',
        });
    });
    image.onload = () => {
        URL.revokeObjectURL(image.src);
        const colors =  colorThief.getPalette(image, swatches);
        palette.html('');
        colors.reduce( (palette,rgb) => {
            const color = rgbToHex( rgb[0],rgb[1],rgb[2] );
            code.push(color);
            palette.append('<div data-grid="'+grid+'" data-color="'+color+'" class="box-clr-att" style="background-color:'+color+';"></div>');
            return palette;
        },palette)
        $('.code-att-'+grid).val(code[0]);
        palette.find('div').first().addClass('active-att');
    }
});
$('body').on('click', '.box-clr-att', function(event) {
    var color = $(this).data('color');
    var grid = $(this).data('grid');
    var input = $('.code-att-'+grid);
    var val = input.val();
    if($(this).hasClass('active-att')){
        //remove
        $(this).removeClass('active-att');
        var valAr = val.split(',');
        $.each(valAr, function(i, v) {
            if ($.trim(v) == $.trim(color)) {
                valAr.splice(i,1);
            }
        });
        input.val(valAr.join(','));
    }else{
        //added
        $(this).addClass('active-att');
        input.val(val+','+color);
    }
});

$.each($('.images-grid'), function(index, val) {
    var img_grid = [];
    $.each($(val).children('img'), function(index, img) {
        img_grid.push($(img).attr('src'));
    });
    $(val).html('');
    $(val).imagesGrid({
        images: img_grid
    });
});
//end select attributes
// image
// with plugin options
// $("input.image").fileinput({"browseLabel":"Browse","cancelLabel":"Cancel","showRemove":true,"showUpload":false,"dropZoneEnabled":false});

/* process_form(); */

</script>
@endpush