@extends($templatePathAdmin.'Layout.main')

@section('main')
<!-- form start -->
<form action="{{ $url_action }}" method="post" accept-charset="UTF-8" class="form-horizontal" id="form-main" enctype="multipart/form-data">
@csrf
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between pb-3 align-content-center">
                <h4 class="card-title align-self-center mb-0">{{ $title }}</h4>
                <a href="{{ bc_route_admin('admin_customer.index') }}" class="btn btn-primary" title="List">
                    <i class="tim-icons icon-minimal-left"></i> 
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="carrd-title">Update Address</h4>
            </div>
            <div class="card-body">
                @if (bc_config_admin('customer_lastname'))
                <div class="row">
                    <div class="col-md-6">    
                        <div class="form-group kind  {{ $errors->has('first_name') ? ' text-red' : '' }}">
                            <label for="first_name">{!! trans('account.first_name') !!}</label>
                            <input type="text"  id="first_name" name="first_name"
                                value="{!! old('first_name',($address['first_name']??'')) !!}" class="form-control first_name" />
                            @include($templatePathAdmin.'Component.feedback',['field'=>'first_name'])
                        </div>
                    </div>
                    <div class="col-md-6">    
                        <div class="form-group kind  {{ $errors->has('last_name') ? ' text-red' : '' }}">
                            <label for="last_name">{!! trans('account.last_name') !!}</label>
                            <input type="text"  id="last_name" name="last_name"
                                value="{!! old('last_name',($address['last_name']??'')) !!}" class="form-control last_name" />
                            @include($templatePathAdmin.'Component.feedback',['field'=>'last_name'])
                        </div>
                    </div>
                </div>
                @else
                <div class="form-group kind  {{ $errors->has('first_name') ? ' text-red' : '' }}">
                    <label for="first_name">{!! trans('account.first_name') !!}</label>
                    <input type="text"  id="first_name" name="first_name"
                        value="{!! old('first_name',($address['first_name']??'')) !!}" class="form-control first_name" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'first_name'])
                </div>
                @endif

                @if (bc_config_admin('customer_name_kana'))
                <div class="row">
                    <div class="col-md-6">   
                        <div class="form-group kind  {{ $errors->has('first_name_kana') ? ' text-red' : '' }}">
                            <label for="first_name_kana">{!! trans('account.first_name_kana') !!}</label>
                            <input type="text"  id="first_name_kana" name="first_name_kana"
                                value="{!! old('first_name_kana',($address['first_name_kana']??'')) !!}" class="form-control first_name_kana" />
                            @include($templatePathAdmin.'Component.feedback',['field'=>'first_name_kana'])
                        </div>
                    </div>
                    <div class="col-md-6">  
                        <div class="form-group kind  {{ $errors->has('last_name_kana') ? ' text-red' : '' }}">
                            <label for="last_name_kana">{!! trans('account.last_name_kana') !!}</label>
                            <input type="text"  id="last_name_kana" name="last_name_kana"
                                value="{!! old('last_name_kana',($address['last_name_kana']??'')) !!}" class="form-control last_name_kana" />
                            @include($templatePathAdmin.'Component.feedback',['field'=>'last_name_kana'])
                        </div>
                    </div>
                </div>
                @endif

                @if (bc_config_admin('customer_phone'))
                <div class="form-group kind  {{ $errors->has('phone') ? ' text-red' : '' }}">
                    <label for="phone">{!! trans('account.phone') !!}</label>
                    <input type="text"  id="phone" name="phone"
                        value="{!! old('phone',($address['phone']??'')) !!}" class="form-control phone" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'phone'])
                </div>
                @endif

                @if (bc_config_admin('customer_postcode'))
                <div class="form-group kind  {{ $errors->has('postcode') ? ' text-red' : '' }}">
                    <label for="postcode">{!! trans('account.postcode') !!}</label>
                    <input type="text"  id="postcode" name="postcode"
                        value="{!! old('postcode',($address['postcode']??'')) !!}" class="form-control postcode" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'postcode'])
                </div>
                @endif
                <div class="form-group kind  {{ $errors->has('address1') ? ' text-red' : '' }}">
                    <label for="address1">{!! trans('account.address1') !!}</label>
                    <input type="text"  id="address1" name="address1"
                        value="{!! old('address1',($address['address1']??'')) !!}" class="form-control address1" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'address1'])
                </div>

                @if (bc_config_admin('customer_address2'))
                <div class="form-group kind  {{ $errors->has('address2') ? ' text-red' : '' }}">
                    <label for="address2">{!! trans('account.address2') !!}</label>
                    <input type="text"  id="address2" name="address2"
                        value="{!! old('address2',($address['address2']??'')) !!}" class="form-control address2" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'address2'])
                </div>
                @endif

                @if (bc_config_admin('customer_address3'))
                <div class="form-group kind  {{ $errors->has('address3') ? ' text-red' : '' }}">
                    <label for="address3">{!! trans('account.address3') !!}</label>
                    <input type="text"  id="address3" name="address3"
                        value="{!! old('address3',($address['address3']??'')) !!}" class="form-control address3" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'address3'])
                </div>
                @endif

                @if (bc_config_admin('customer_country'))
                    {{-- Country --}}
                    <div class="form-group kind  {{ $errors->has('country') ? ' text-red' : '' }}">
                        <label for="country">{{ trans('account.country') }}</label>
                        <select class="form-control country selectpicker"
                            data-live-search="true"
                            name="country">
                            @foreach ($countries as $k => $v)
                                <option value="{{ $k }}"
                                    {{ (old('country', $address['country']??'') ==$k) ? 'selected':'' }}>{{ $v }}
                                </option>
                            @endforeach
                        </select>
                        @include($templatePathAdmin.'Component.feedback',['field'=>'country'])
                    </div>
                    {{-- //Country --}}
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Hoàn Tất</h4>
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

@push('scripts')
<script type="text/javascript">
    $('.submit').click(function(event) {
        $('#form-main').submit();
    });
</script>
@endpush