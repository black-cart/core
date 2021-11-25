@extends($templatePathAdmin.'Layout.main')

@section('main')
   <div class="row">
        <div class="col-md-12">
            <form action="{{ bc_route_admin('admin_order.create') }}" method="post" accept-charset="UTF-8" class="form-horizontal" id="form-main">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-content-center">
                    <h4 class="card-title">{{ $title }}</h4>
                    <a href="{{ bc_route_admin('admin_order.index') }}" class="btn btn-primary" title="List">
                        <i class="tim-icons icon-minimal-left"></i> 
                    </a>
                </div>
                <div class="card-body">

                    <input type="hidden" name="email">
                    

                    <div class="row">
                        <label class="col-sm-2 col-form-label">{{ trans('order.select_customer') }}</label>
                        <div class="col-sm-10">
                            <div class="form-group {{ $errors->has('first_name') ? ' text-red' : '' }}">
                                <select class="form-control customer_id selectpicker" name="customer_id" >
                                    <option disabled selected>Vui lòng chọn khách hàng</option>
                                    @foreach ($users as $k => $v)
                                        <option value="{{ $k }}" {{ (old('customer_id') ==$k) ? 'selected':'' }}>{{ $v->name.'<'.$v->email.'>' }}</option>
                                    @endforeach
                                </select>
                                @include($templatePathAdmin.'Component.feedback',['field'=>'customer_id'])
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-2 col-form-label">{{ trans('order.shipping_first_name') }}</label>
                        <div class="col-sm-10">
                            <div class="form-group {{ $errors->has('first_name') ? ' text-red' : '' }}">
                                <input type="text" id="first_name" name="first_name" value="{!! old('first_name') !!}" class="form-control first_name" placeholder="" />
                                @include($templatePathAdmin.'Component.feedback',['field'=>'first_name'])
                            </div>
                        </div>
                    </div>

                    @if (bc_config_admin('customer_lastname'))
                        <div class="row">
                            <label class="col-sm-2 col-form-label">{{ trans('order.shipping_last_name') }}</label>
                            <div class="col-sm-10">
                                <div class="form-group {{ $errors->has('last_name') ? ' text-red' : '' }}">
                                    <input type="text" id="last_name" name="last_name" value="{!! old('last_name') !!}" class="form-control last_name" placeholder="" />
                                    @include($templatePathAdmin.'Component.feedback',['field'=>'last_name'])
                                </div>
                            </div>
                        </div>    
                    @endif

                    @if (bc_config_admin('customer_name_kana'))
                        <div class="row">
                            <label class="col-sm-2 col-form-label">{{ trans('order.shipping_first_name_kana') }}</label>
                            <div class="col-sm-10">
                                <div class="form-group {{ $errors->has('first_name_kana') ? ' text-red' : '' }}">
                                    <input type="text" id="first_name_kana" name="first_name_kana" value="{!! old('first_name_kana') !!}" class="form-control first_name_kana" placeholder="" />
                                    @include($templatePathAdmin.'Component.feedback',['field'=>'first_name_kana'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-sm-2 col-form-label">{{ trans('order.shipping_last_name_kana') }}</label>
                            <div class="col-sm-10">
                                <div class="form-group {{ $errors->has('last_name_kana') ? ' text-red' : '' }}">
                                    <input type="text" id="last_name_kana" name="last_name_kana" value="{!! old('last_name_kana') !!}" class="form-control last_name_kana" placeholder="" />
                                    @include($templatePathAdmin.'Component.feedback',['field'=>'last_name_kana'])
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (bc_config_admin('customer_company'))
                        <div class="row">
                            <label class="col-sm-2 col-form-label">{{ trans('order.company') }}</label>
                            <div class="col-sm-10">
                                <div class="form-group {{ $errors->has('company') ? ' text-red' : '' }}">
                                    <input type="text" id="company" name="company" value="{!! old('company') !!}" class="form-control company" placeholder="" />
                                    @include($templatePathAdmin.'Component.feedback',['field'=>'company'])
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (bc_config_admin('customer_postcode'))
                        <div class="row">
                            <label class="col-sm-2 col-form-label">{{ trans('order.postcode') }}</label>
                            <div class="col-sm-10">
                                <div class="form-group {{ $errors->has('postcode') ? ' text-red' : '' }}">
                                    <input type="text" id="postcode" name="postcode" value="{!! old('postcode') !!}" class="form-control postcode" placeholder="" />
                                    @include($templatePathAdmin.'Component.feedback',['field'=>'postcode'])
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <label class="col-sm-2 col-form-label">{{ trans('order.shipping_address1') }}</label>
                        <div class="col-sm-10">
                            <div class="form-group {{ $errors->has('address1') ? ' text-red' : '' }}">
                                <input type="text" id="address1" name="address1" value="{!! old('address1') !!}" class="form-control address1" placeholder="" />
                                @include($templatePathAdmin.'Component.feedback',['field'=>'address1'])
                            </div>
                        </div>
                    </div>

                    @if (bc_config_admin('customer_address2'))   
                        <div class="row">
                            <label class="col-sm-2 col-form-label">{{ trans('order.shipping_address2') }}</label>
                            <div class="col-sm-10">
                                <div class="form-group {{ $errors->has('address2') ? ' text-red' : '' }}">
                                    <input type="text" id="address2" name="address2" value="{!! old('address2') !!}" class="form-control address2" placeholder="" />
                                    @include($templatePathAdmin.'Component.feedback',['field'=>'address2'])
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (bc_config_admin('customer_address3'))    
                        <div class="row">
                            <label class="col-sm-2 col-form-label">{{ trans('order.shipping_address3') }}</label>
                            <div class="col-sm-10">
                                <div class="form-group {{ $errors->has('address3') ? ' text-red' : '' }}">
                                    <input type="text" id="address3" name="address3" value="{!! old('address3') !!}" class="form-control address3" placeholder="" />
                                    @include($templatePathAdmin.'Component.feedback',['field'=>'address3'])
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (bc_config_admin('customer_country'))
                        <div class="row">
                            <label class="col-sm-2 col-form-label">{{ trans('order.country') }}</label>
                            <div class="col-sm-10">
                                <div class="form-group {{ $errors->has('country') ? ' text-red' : '' }}">
                                    <select class="form-control country selectpicker" name="country" >
                                        <option disabled selected>Vui lòng chọn quốc gia</option>
                                        @foreach ($countries as $k => $v)
                                            <option value="{{ $k }}" {{ (old('country') ==$k) ? 'selected':'' }}>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                    @include($templatePathAdmin.'Component.feedback',['field'=>'country'])
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (bc_config_admin('customer_phone'))
                        <div class="row">
                            <label class="col-sm-2 col-form-label">{{ trans('order.shipping_phone') }}</label>
                            <div class="col-sm-10">
                                <div class="form-group {{ $errors->has('phone') ? ' text-red' : '' }}">
                                    <input type="text" id="phone" name="phone" value="{!! old('phone') !!}" class="form-control phone" placeholder="" />
                                    @include($templatePathAdmin.'Component.feedback',['field'=>'phone'])
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <label class="col-sm-2 col-form-label">{{ trans('order.currency') }}</label>
                        <div class="col-sm-10">
                            <div class="form-group {{ $errors->has('currency') ? ' text-red' : '' }}">
                                <select class="form-control currency selectpicker" name="currency" >
                                    <option disabled selected>Vui lòng chọn loại tiền</option>
                                    @foreach ($currencies as  $v)
                                        <option value="{{ $v->code }}" {{ (old('currency') == $v->code) ? 'selected':'' }}>{{ $v->name}}</option>
                                    @endforeach
                                </select>
                                @include($templatePathAdmin.'Component.feedback',['field'=>'currency'])
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-2 col-form-label">{{ trans('order.exchange_rate') }}</label>
                        <div class="col-sm-10">
                            <div class="form-group {{ $errors->has('exchange_rate') ? ' text-red' : '' }}">
                                <input type="text" id="exchange_rate" name="exchange_rate" value="{!! old('exchange_rate') !!}" class="form-control exchange_rate" placeholder="Input Exchange rate" />
                                @include($templatePathAdmin.'Component.feedback',['field'=>'exchange_rate'])
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-2 col-form-label">{{ trans('order.note') }}</label>
                        <div class="col-sm-10">
                            <div class="form-group {{ $errors->has('exchange_rate') ? ' text-red' : '' }}">
                                <textarea name="comment" class="form-control comment" rows="5" placeholder="">{!! old('comment') !!}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-2 col-form-label">{{ trans('order.payment_method') }}</label>
                        <div class="col-sm-10">
                            <div class="form-group {{ $errors->has('payment_method') ? ' text-red' : '' }}">
                                <select class="form-control payment_method selectpicker" name="payment_method" >
                                    <option disabled selected>Vui lòng chọn Phương thức thanh toán</option>
                                    @foreach ($paymentMethod as $k => $v)
                                        <option value="{{ $k }}" {{ (old('payment_method') ==$k) ? 'selected':'' }}>{{ bc_language_render($v)}}</option>
                                    @endforeach
                                </select>
                                @include($templatePathAdmin.'Component.feedback',['field'=>'payment_method'])
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-2 col-form-label">{{ trans('order.shipping_method') }}</label>
                        <div class="col-sm-10">
                            <div class="form-group {{ $errors->has('shipping_method') ? ' text-red' : '' }}">
                                <select class="form-control shipping_method selectpicker" name="shipping_method" >
                                    <option disabled selected>Vui lòng chọn Phương thức vận chuyển</option>
                                    @foreach ($shippingMethod as $k => $v)
                                        <option value="{{ $k }}" {{ (old('shipping_method') ==$k) ? 'selected':'' }}>{{ bc_language_render($v)}}</option>
                                    @endforeach
                                </select>
                                @include($templatePathAdmin.'Component.feedback',['field'=>'shipping_method'])
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-2 col-form-label">{{ trans('order.status') }}</label>
                        <div class="col-sm-10">
                            <div class="form-group {{ $errors->has('status') ? ' text-red' : '' }}">
                                <select class="form-control status selectpicker" name="status" >
                                    <option disabled selected>Vui lòng chọn trạng thái đơn hàng</option>
                                    @foreach ($orderStatus as $k => $v)
                                        <option value="{{ $k }}" {{ (old('status') ==$k) ? 'selected':'' }}>{{ $v}}</option>
                                    @endforeach
                                </select>
                                @include($templatePathAdmin.'Component.feedback',['field'=>'status'])
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer text-right">
                    <div class="btn-group">
                        @csrf
                        <button type="reset" class="btn btn-warning grid-select-all"><i class="tim-icons icon-refresh-01"></i> {{ trans('admin.reset') }}</button>
                        <button type="submit" class="btn btn-success grid-trash"><i class="tim-icons icon-check-2"></i> {{ trans('admin.submit') }}</button>
                    </div>
                </div>
              </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')

@endpush

@push('scripts')


<script type="text/javascript">

$('[name="customer_id"]').change(function(){
    addInfo();
});
$('[name="currency"]').change(function(){
    addExchangeRate();
});

function addExchangeRate(){
    var currency = $('[name="currency"]').val();
    var jsonCurrency = {!!$currenciesRate !!};
    $('[name="exchange_rate"]').val(jsonCurrency[currency]);
}

function addInfo(){
    id = $('[name="customer_id"]').val();
    if(id){
       $.ajax({
            url : '{{ bc_route_admin('admin_order.user_info') }}',
            type : "get",
            dateType:"application/json; charset=utf-8",
            data : {
                 id : id
            },
            beforeSend: function(){
                $('#loading').show();
            },
            success: function(result){
                var returnedData = JSON.parse(result);
                $('[name="first_name"]').val(returnedData.first_name);
                $('[name="last_name"]').val(returnedData.last_name);
                $('[name="first_name_kana"]').val(returnedData.first_name_kana);
                $('[name="last_name_kana"]').val(returnedData.last_name_kana);
                $('[name="address1"]').val(returnedData.address1);
                $('[name="address2"]').val(returnedData.address2);
                $('[name="address3"]').val(returnedData.address3);
                $('[name="phone"]').val(returnedData.phone);
                $('[name="company"]').val(returnedData.company);
                $('[name="postcode"]').val(returnedData.postcode);
                $('[name="country"]').val(returnedData.country).change();
                $('#loading').hide();
            }
        });
       }else{
            $('#form-main').reset();
       }

}

</script>
@endpush
