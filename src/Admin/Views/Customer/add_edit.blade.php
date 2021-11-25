@extends($templatePathAdmin.'Layout.main')

@section('main')
<!-- form start -->
<form action="{{ $url_action }}" method="post" accept-charset="UTF-8" class="form-horizontal" id="form-main" enctype="multipart/form-data">
@csrf
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between pb-3 align-content-center">
                <h4 class="card-title align-self-center mb-0">{{ $title_description }}</h4>
                <a href="{{ bc_route_admin('admin_customer.index') }}" class="btn btn-primary" title="List">
                    <i class="tim-icons icon-minimal-left"></i> 
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="carrd-title">Thông tin Chung</h4>
            </div>
            <div class="card-body">
                @if (bc_config_admin('customer_lastname'))
                <div class="row">
                    <div class="col-md-6">    
                        <div class="form-group kind  {{ $errors->has('first_name') ? ' text-red' : '' }}">
                            <label for="first_name">{!! trans('account.first_name') !!}</label>
                            <input type="text"  id="first_name" name="first_name"
                                value="{!! old('first_name',($customer['first_name']??'')) !!}" class="form-control first_name" />
                            @include($templatePathAdmin.'Component.feedback',['field'=>'first_name'])
                        </div>
                    </div>
                    <div class="col-md-6">    
                        <div class="form-group kind  {{ $errors->has('last_name') ? ' text-red' : '' }}">
                            <label for="last_name">{!! trans('account.last_name') !!}</label>
                            <input type="text"  id="last_name" name="last_name"
                                value="{!! old('last_name',($customer['last_name']??'')) !!}" class="form-control last_name" />
                            @include($templatePathAdmin.'Component.feedback',['field'=>'last_name'])
                        </div>
                    </div>
                </div>
                @else
                <div class="form-group kind  {{ $errors->has('first_name') ? ' text-red' : '' }}">
                    <label for="first_name">{!! trans('account.first_name') !!}</label>
                    <input type="text"  id="first_name" name="first_name"
                        value="{!! old('first_name',($customer['first_name']??'')) !!}" class="form-control first_name" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'first_name'])
                </div>
                @endif

                @if (bc_config_admin('customer_name_kana'))
                <div class="row">
                    <div class="col-md-6">   
                        <div class="form-group kind  {{ $errors->has('first_name_kana') ? ' text-red' : '' }}">
                            <label for="first_name_kana">{!! trans('account.first_name_kana') !!}</label>
                            <input type="text"  id="first_name_kana" name="first_name_kana"
                                value="{!! old('first_name_kana',($customer['first_name_kana']??'')) !!}" class="form-control first_name_kana" />
                            @include($templatePathAdmin.'Component.feedback',['field'=>'first_name_kana'])
                        </div>
                    </div>
                    <div class="col-md-6">  
                        <div class="form-group kind  {{ $errors->has('last_name_kana') ? ' text-red' : '' }}">
                            <label for="last_name_kana">{!! trans('account.last_name_kana') !!}</label>
                            <input type="text"  id="last_name_kana" name="last_name_kana"
                                value="{!! old('last_name_kana',($customer['last_name_kana']??'')) !!}" class="form-control last_name_kana" />
                            @include($templatePathAdmin.'Component.feedback',['field'=>'last_name_kana'])
                        </div>
                    </div>
                </div>
                @endif

                @if (bc_config_admin('customer_phone'))
                <div class="form-group kind  {{ $errors->has('phone') ? ' text-red' : '' }}">
                    <label for="phone">{!! trans('account.phone') !!}</label>
                    <input type="text"  id="phone" name="phone"
                        value="{!! old('phone',($customer['phone']??'')) !!}" class="form-control phone" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'phone'])
                </div>
                @endif

                @if (bc_config_admin('customer_postcode'))
                <div class="form-group kind  {{ $errors->has('postcode') ? ' text-red' : '' }}">
                    <label for="postcode">{!! trans('account.postcode') !!}</label>
                    <input type="text"  id="postcode" name="postcode"
                        value="{!! old('postcode',($customer['postcode']??'')) !!}" class="form-control postcode" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'postcode'])
                </div>
                @endif
                <div class="form-group kind  {{ $errors->has('email') ? ' text-red' : '' }}">
                    <label for="email">{!! trans('account.email') !!}</label>
                    <input type="text"  id="email" name="email"
                        value="{!! old('email',($customer['email']??'')) !!}" class="form-control email" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'email'])
                </div>
                <div class="form-group kind  {{ $errors->has('address1') ? ' text-red' : '' }}">
                    <label for="address1">{!! trans('account.address1') !!}</label>
                    <input type="text"  id="address1" name="address1"
                        value="{!! old('address1',($customer['address1']??'')) !!}" class="form-control address1" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'address1'])
                </div>

                @if (bc_config_admin('customer_address2'))
                <div class="form-group kind  {{ $errors->has('address2') ? ' text-red' : '' }}">
                    <label for="address2">{!! trans('account.address2') !!}</label>
                    <input type="text"  id="address2" name="address2"
                        value="{!! old('address2',($customer['address2']??'')) !!}" class="form-control address2" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'address2'])
                </div>
                @endif

                @if (bc_config_admin('customer_address3'))
                <div class="form-group kind  {{ $errors->has('address3') ? ' text-red' : '' }}">
                    <label for="address3">{!! trans('account.address3') !!}</label>
                    <input type="text"  id="address3" name="address3"
                        value="{!! old('address3',($customer['address3']??'')) !!}" class="form-control address3" />
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
                                    {{ (old('country', $customer['country']??'') ==$k) ? 'selected':'' }}>{{ $v }}
                                </option>
                            @endforeach
                        </select>
                        @include($templatePathAdmin.'Component.feedback',['field'=>'country'])
                    </div>
                    {{-- //Country --}}
                @endif
                @if (bc_config_admin('customer_sex'))
                    <div class="form-group">
                        <p class="sex">{{ trans('account.sex') }}</p>
                        <input type="checkbox" 
                        {{ old('sex',(empty($customer['sex'])?0:1))?'checked':''}} name="sex" class="bootstrap-switch" data-on-label="{{ trans('account.sex_men') }}" data-off-label="{{ trans('account.sex_women') }}">
                    </div>
                    @include($templatePathAdmin.'Component.feedback',['field'=>'sex'])
                @endif

                @if (bc_config_admin('customer_birthday'))
                    <div class="form-group kind {{ $errors->has('birthday') ? ' text-red' : '' }}">
                        <label for="birthday">{{ trans('account.birthday') }}</label>
                            <input type="text" id="birthday" name="birthday"
                                    value="{{old('birthday',$customer['birthday'] ?? '')}}"
                                    class="form-control birthday datetimepicker"/>
                            @include($templatePathAdmin.'Component.feedback',['field'=>'birthday'])
                    </div>
                @endif

                @if (bc_config_admin('customer_group'))
                <div class="form-group kind  {{ $errors->has('group') ? ' text-red' : '' }}">
                    <label for="group">{!! trans('account.group') !!}</label>
                    <input type="number"  id="group" name="group"
                        value="{!! old('group',($customer['group']??'')) !!}" class="form-control group" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'group'])
                </div>
                @endif

                <div class="form-group kind  {{ $errors->has('password') ? ' text-red' : '' }}">
                    <label for="password">{!! trans('customer.password') !!}</label>
                    <input type="text"  id="password" name="password"
                        value="{{ old('password')??'' }}" class="form-control password" />
                    @include($templatePathAdmin.'Component.feedback',['field'=>'password'])
                </div>
                {{-- Custom fields --}}
                @if ($customFields)
                <hr class="kind ">
                    <div class="form-group">
                        <label for="custom_field">
                            {{ trans('custom_field.admin.title') }} 
                            (<a target=_new href="{{ bc_route_admin('admin_custom_field.index') }}"><i class="fa fa-link" aria-hidden="true"></i></a>)
                        </label>
                    </div>
                    @foreach ($customFields as $keyField => $field)
                    @php
                        $default  = json_decode($field->default, true)
                    @endphp
                    <div class="form-group kind   {{ $errors->has('fields.'.$field->code) ? ' text-red' : '' }}">
                        <label for="{{ $field->code }}">{{ bc_language_render($field->name) }}</label>
                        @if ($field->option == 'radio')
                            @if ($default)
                            @foreach ($default as $key => $name)
                            <div class="form-check form-check-radio">
                                <label class="form-check-label" for="{{ $keyField.'__'.$key }}">
                                  <input class="form-check-input" type="radio" name="fields[{{ $field->code }}]" id="{{ $keyField.'__'.$key }}" value="{{ $key }}" {{ (old('fields.'.$field->code) == $key)?'checked':'' }}>
                                  <span class="form-check-sign"></span>
                                  {{ $name }}
                                </label>
                            </div>
                            @endforeach
                            @endif
                        @elseif($field->option == 'select')
                            @if ($default)
                                <select class="form-control country selectpicker {{ $field->code }}" name="fields[{{ $field->code }}]">
                                <option value=""></option>
                                    @foreach ($default as $key => $name)
                                        <option value="{{ $key }}" {{ (old('fields.'.$field->code) == $key) ? 'selected':'' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        @else
                            <input type="text" id="field_{{ $field->code }}" name="fields[{{ $field->code }}]"
                                value="{{ old('fields.'.$field->code) }}" class="form-control {{ $field->code }}" />
                        @endif

                        @include($templatePathAdmin.'Component.feedback',['field'=>'fields.'.$field->code])
                    </div>
                    @endforeach
                @endif
                {{-- //Custom fields --}}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        @if (!$addresses->isEmpty())
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ trans('account.address_list') }}</h4>
                </div>
                <div class="card-body">
                @foreach($addresses as $address)
                    <table class="table tablesorter tablesorter-default" id="simple-table" role="grid">
                        <tbody aria-live="polite" aria-relevant="all">
                            @if (bc_config_admin('customer_lastname'))
                                <tr role="row">
                                    <th>
                                      {{ trans('account.first_name') }}
                                    </th>
                                    <td>
                                      {{ $address['first_name'] }}
                                    </td>
                                </tr>
                                <tr role="row">
                                    <th>
                                      {{ trans('account.last_name') }}
                                    </th>
                                    <td>
                                      {{ $address['last_name'] }}
                                    </td>
                                </tr>
                            @else
                                <tr role="row">
                                    <th>
                                      {{ trans('account.name') }}
                                    </th>
                                    <td>
                                      {{ $address['first_name'] }}
                                    </td>
                                </tr>
                            @endif
                            @if (bc_config_admin('customer_phone'))
                                <tr role="row">
                                    <th>
                                      {{ trans('account.phone') }}
                                    </th>
                                    <td>
                                      {{ $address['phone'] }}
                                    </td>
                                </tr>
                            @endif
                            @if (bc_config_admin('customer_postcode'))
                                <tr role="row">
                                    <th>
                                      {{ trans('account.postcode') }}
                                    </th>
                                    <td>
                                      {{ $address['postcode'] }}
                                    </td>
                                </tr>
                            @endif
                            <tr role="row">
                                <th>
                                  {{ trans('account.address1') }}
                                </th>
                                <td>
                                  {{ $address['address1'] }}
                                </td>
                            </tr>
                            @if (bc_config_admin('customer_address2'))
                                <tr role="row">
                                    <th>
                                      {{ trans('account.address2') }}
                                    </th>
                                    <td>
                                      {{ $address['address2'] }}
                                    </td>
                                </tr>
                            @endif

                            @if (bc_config_admin('customer_address3'))
                                <tr role="row">
                                    <th>
                                      {{ trans('account.address3') }}
                                    </th>
                                    <td>
                                      {{ $address['address3'] }}
                                    </td>
                                </tr>
                            @endif
                            @if (bc_config_admin('customer_country'))
                                <tr role="row">
                                    <th>
                                      {{ trans('account.country') }}
                                    </th>
                                    <td>
                                      {{ $countries[$address['country']] ?? $address['country'] }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            
                <div class="card-footer">
                    <div class="btn-group">
                        <a href="{{ bc_route_admin('admin_customer.update_address', ['id' => $address->id]) }}" class="btn btn-primary" 
                            title="{{ trans('account.addresses.edit')}}">
                            <i class="tim-icons icon-pencil"></i>
                        </a>
                        <a href="#" data-id="{{ $address->id }}" class="btn btn-warning delete-address" title="{{ trans('account.addresses.delete') }}">
                            <i class="tim-icons icon-simple-remove"></i>
                        </a>
                        @if ($address->id == $customer['address_id'])
                            <a href="#" class="btn btn-info" title="{{ trans('account.addresses.default') }}">
                                <i class="fa fa-university"></i>
                            </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Hoàn Tất</h4>
            </div>
            <div class="card-body">
                {{-- status --}}
                    <div class="form-group">
                        <p class="status">{{ trans('customer.status') }}</p>
                        <input type="checkbox" 
                        {{ old('status',(empty($customer['status'])?0:1))?'checked':''}} name="status" class="bootstrap-switch" data-on-label="ON" data-off-label="OFF">
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
    <style type="text/css">
        .hidden{
            display: none;
        }
        .bs-searchbox .form-control,.bs-searchbox~.dropdown-menu .no-results{
            color: #292b2c;
        }
    </style>
@endpush
@push('scripts')
@if (bc_config_admin('customer_birthday'))
    <script src="{{ asset('admin/black/js/plugins/moment.min.js') }}"></script>
    <script src="{{ asset('admin/black/js/plugins/bootstrap-datetimepicker.js') }}"></script>
    <script type="text/javascript">blackDashboard.initDateTimePicker();</script>
@endif
<script type="text/javascript">
    $('.submit').click(function(event) {
        $('#form-main').submit();
    });
    $('.delete-address').click(function(){
        var r = confirm("{{ trans('account.confirm_delete') }}");
        if(!r) {
            return;
        }
        var id = $(this).data('id');
        $.ajax({
            url:'{{ route("admin_customer.delete_address") }}',
            type:'POST',
            dataType:'json',
            data:{id:id,"_token": "{{ csrf_token() }}"},
                  beforeSend: function(){
                  $('#loading').show();
            },
            success: function(data){
                if(data.error == 0) {
                  location.reload();
                }
            }
        });
    });
</script>
@endpush