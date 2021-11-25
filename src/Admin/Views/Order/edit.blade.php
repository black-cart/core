@extends($templatePathAdmin.'Layout.main')

@section('main')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header p-3 d-flex justify-content-between align-content-center">
                <h3 class="mb-0 align-self-center card-title">{{ trans('order.order_detail') }} #{{ $order->id }}</h3>
                
                <div class="btn-group">
                    <a href="{{ bc_route_admin('admin_order.index') }}" class="btn btn-primary" title="List">
                        <i class="tim-icons icon-minimal-left"></i> 
                    </a>
                    <a href="{{ bc_route_admin('admin_order.export_detail').'?order_id='.$order->id.'&type=invoice' }}" title="Export" class="btn btn-success">
                        <i class="fas fa-file-excel"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="order_print()" class="btn btn-info" title="print">
                        <i class="fa fa-print"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Thông tin khách hàng</h4>
            </div>
            <div class="card-body">
                 <table class="table tablesorter tablesorter-default">
                    <tbody>
                    <tr>
                        <td class="td-title">{{ trans('order.shipping_first_name') }}:</td>
                        <td>
                            <a href="#" class="updateInfoRequired" 
                                data-name="first_name" 
                                data-type="text" 
                                data-pk="{{ $order->id }}" 
                                data-url="{{ route("admin_order.update") }}" 
                                data-title="{{ trans('order.shipping_first_name') }}" >
                                {!! $order->first_name !!}
                            </a>
                        </td>
                    </tr>

                    @if (bc_config_admin('customer_lastname'))
                    <tr>
                        <td class="td-title">{{ trans('order.shipping_last_name') }}:</td>
                        <td>
                            <a href="#" class="updateInfoRequired" 
                                data-name="last_name" 
                                data-type="text" 
                                data-pk="{{ $order->id }}" 
                                data-url="{{ route("admin_order.update") }}" 
                                data-title="{{ trans('order.shipping_last_name') }}" >
                                {!! $order->last_name !!}
                            </a>
                        </td>
                    </tr>
                    @endif

                    @if (bc_config_admin('customer_phone'))
                    <tr>
                        <td class="td-title">{{ trans('order.shipping_phone') }}:</td>
                        <td>
                            <a href="#" class="updateInfoRequired" 
                                data-name="phone" 
                                data-type="text" 
                                data-pk="{{ $order->id }}" 
                                data-url="{{ route("admin_order.update") }}" 
                                data-title="{{ trans('order.shipping_phone') }}" >
                                {!! $order->phone !!}
                            </a>
                        </td>
                    </tr>
                    @endif

                    <tr>
                        <td class="td-title">{{ trans('order.email') }}:</td>
                        <td>
                            {!! empty($order->email)?'N/A':$order->email!!}
                        </td>
                    </tr>

                    @if (bc_config_admin('customer_company'))
                    <tr>
                        <td class="td-title">{{ trans('order.company') }}:</td>
                        <td>
                            <a href="#" class="updateInfoRequired" 
                                data-name="company" 
                                data-type="text" 
                                data-pk="{{ $order->id }}" 
                                data-url="{{ route("admin_order.update") }}" 
                                data-title="{{ trans('order.company') }}" >
                                {!! $order->company !!}
                            </a>
                        </td>
                    </tr>
                    @endif

                    @if (bc_config_admin('customer_postcode'))
                    <tr>
                        <td class="td-title">{{ trans('order.postcode') }}:</td>
                        <td>
                            <a href="#" class="updateInfoRequired" 
                                data-name="postcode" 
                                data-type="text" 
                                data-pk="{{ $order->id }}" 
                                data-url="{{ route("admin_order.update") }}" 
                                data-title="{{ trans('order.postcode') }}" >
                                {!! $order->postcode !!}
                            </a>
                        </td>
                    </tr>
                    @endif

                    <tr>
                        <td class="td-title">{{ trans('order.shipping_address1') }}:</td>
                        <td><a href="#" class="updateInfoRequired" 
                                data-name="address1" 
                                data-type="text" 
                                data-pk="{{ $order->id }}" 
                                data-url="{{ route("admin_order.update") }}" 
                                data-title="{{ trans('order.address1') }}" >
                                {!! $order->address1 !!}
                            </a>
                        </td>
                    </tr>

                    @if (bc_config_admin('customer_address2'))
                    <tr>
                        <td class="td-title">{{ trans('order.shipping_address2') }}:</td>
                        <td><a href="#" class="updateInfoRequired" 
                                data-name="address2" 
                                data-type="text" 
                                data-pk="{{ $order->id }}" 
                                data-url="{{ route("admin_order.update") }}" 
                                data-title="{{ trans('order.address2') }}" >
                                {!! $order->address2 !!}
                            </a>
                        </td>
                    </tr>
                    @endif

                    @if (bc_config_admin('customer_address3'))
                    <tr>
                        <td class="td-title">{{ trans('order.shipping_address3') }}:</td>
                        <td><a href="#" class="updateInfoRequired" 
                                data-name="address3" 
                                data-type="text" 
                                data-pk="{{ $order->id }}" 
                                data-url="{{ route("admin_order.update") }}" 
                                data-title="{{ trans('order.address3') }}" >
                                {!! $order->address3 !!}
                            </a>
                        </td>
                    </tr>
                    @endif

                    @if (bc_config_admin('customer_country'))
                    <tr>
                        <td class="td-title">{{ trans('order.country') }}:</td>
                        <td><a href="#" class="updateInfoRequired" 
                            data-name="country" 
                            data-type="select" 
                            data-source ="{{ json_encode($country) }}" 
                            data-pk="{{ $order->id }}" 
                            data-url="{{ route("admin_order.update") }}" 
                            data-title="{{ trans('order.country') }}" 
                            data-value="{!! $order->country !!}">
                            </a>
                        </td>
                    </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-body mb-0">
                 <table class="table tablesorter tablesorter-default">
                    <tr>
                        <td  class="td-title">{{ trans('order.order_note') }}:</td>
                        <td>
                            <a href="#" class="updateInfo" data-name="comment" 
                                data-type="text" data-pk="{{ $order->id }}" 
                                data-url="{{ route("admin_order.update") }}" data-title="" >
                                {{ $order->comment }}
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Thông tin Đơn hàng</h4>
            </div>
            <div class="card-body">
                <table  class="table tablesorter tablesorter-default">
                    <tr>
                        <td  class="td-title">{{ trans('order.order_status') }}:</td>
                        <td><a href="#" class="updateStatus" 
                                data-name="status" 
                                data-type="select" 
                                data-source ="{{ json_encode($statusOrder) }}"  
                                data-pk="{{ $order->id }}" 
                                data-value="{!! $order->status !!}" 
                                data-url="{{ route("admin_order.update") }}" 
                                data-title="{{ trans('order.order_status') }}">
                                {{ $statusOrder[$order->status] }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>{{ trans('order.order_shipping_status') }}:</td>
                        <td><a href="#" class="updateStatus" 
                                data-name="shipping_status" 
                                data-type="select" 
                                data-source ="{{ json_encode($statusShipping) }}"  
                                data-pk="{{ $order->id }}" 
                                data-value="{!! $order->shipping_status !!}" 
                                data-url="{{ route("admin_order.update") }}" 
                                data-title="{{ trans('order.order_shipping_status') }}">
                                {{ $statusShipping[$order->shipping_status]??'' }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>{{ trans('order.order_payment_status') }}:</td>
                        <td><a href="#" class="updateStatus" 
                                data-name="payment_status" 
                                data-type="select" 
                                data-source ="{{ json_encode($statusPayment) }}"  
                                data-pk="{{ $order->id }}" 
                                data-value="{!! $order->payment_status !!}" 
                                data-url="{{ route("admin_order.update") }}" 
                                data-title="{{ trans('order.order_payment_status') }}">
                                {{ $statusPayment[$order->payment_status]??'' }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>{{ trans('order.shipping_method') }}:</td>
                        <td><a href="#" class="updateStatus" 
                                data-name="shipping_method" 
                                data-type="select" 
                                data-source ="{{ json_encode($shippingMethod) }}"  
                                data-pk="{{ $order->id }}" 
                                data-value="{!! $order->shipping_method !!}" 
                                data-url="{{ route("admin_order.update") }}" 
                                data-title="{{ trans('order.shipping_method') }}">
                                {{ $order->shipping_method }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>{{ trans('order.payment_method') }}:</td>
                        <td><a href="#" class="updateStatus" 
                                data-name="payment_method" 
                                data-type="select" 
                                data-source ="{{ json_encode($paymentMethod) }}"  
                                data-pk="{{ $order->id }}" 
                                data-value="{!! $order->payment_method !!}" 
                                data-url="{{ route("admin_order.update") }}" 
                                data-title="{{ trans('order.payment_method') }}">
                                {{ $order->payment_method }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>{{ trans('order.domain') }}:</td>
                        <td>{{ $order->domain }}</td>
                    </tr>
                    <tr>
                        <td></i> {{ trans('order.created_at') }}:</td>
                        <td>{{ $order->created_at }}</td>
                    </tr>
                    <tr>
                        <td class="td-title"><i class="tim-icons icon-money-coins"></i> {{ trans('order.currency') }}:</td>
                        <td>{{ $order->currency }}</td>
                    </tr>
                    <tr>
                        <td class="td-title"><i class="fas fa-chart-line"></i> {{ trans('order.exchange_rate') }}:</td>
                        <td>{{ ($order->exchange_rate)??1 }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header p-3 d-flex justify-content-between align-content-center">
                <h4 class="mb-0 align-self-center card-title">Thông tin sản phẩm</h4>
                <button  type="button" 
                    class="btn btn-primary" 
                    id="add-item-button"  
                    title="{{trans('product.add_product') }}">
                    <i class="fa fa-plus"></i> 
                    {{ trans('product.add_product') }}
                </button>
            </div>
            <div class="card-body">
                <form id="form-add-item" action="" method="">
                    @csrf
                    <input type="hidden" name="order_id"  value="{{ $order->id }}">
                    <table class="table tablesorter tablesorter-default">
                        <thead class="">
                            <tr>
                                <th>{{ trans('product.name') }}</th>
                                <th>{{ trans('product.sku') }}</th>
                                <th class="product_price">{{ trans('product.price') }}</th>
                                <th class="product_qty">{{ trans('product.quantity') }}</th>
                                <th class="product_total">{{ trans('product.total_price') }}</th>
                                <th class="product_tax">{{ trans('product.tax') }}</th>
                                <th>{{ trans('admin.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->details as $item)
                                <tr>
                                    <td>{{ $item->name }}
                                        @php
                                        $html = '';
                                        if($item->attribute && is_array(json_decode($item->attribute,true))){
                                            $array = json_decode($item->attribute,true);
                                            foreach ($array as $key => $element){
                                                $html .= '<br><b>'.$attributesGroup[$key].'</b> : <i>'.bc_render_option_price($element, $order->currency, $order->exchange_rate).'</i>';
                                            }
                                        }
                                        @endphp
                                        {!! $html !!}
                                    </td>
                                    <td>{{ $item->sku }}</td>
                                    <td class="product_price">
                                        <a href="#" class="edit-item-detail" 
                                            data-value="{{ $item->price }}" 
                                            data-name="price" 
                                            data-type="number" min=0 
                                            data-pk="{{ $item->id }}" 
                                            data-url="{{ route("admin_order.edit_item") }}" 
                                            data-title="{{ trans('product.price') }}">
                                            {{ $item->price }}
                                        </a>
                                    </td>
                                    <td class="product_qty">x <a href="#" class="edit-item-detail" 
                                            data-value="{{ $item->qty }}" 
                                            data-name="qty" 
                                            data-type="number" min=0 
                                            data-pk="{{ $item->id }}" 
                                            data-url="{{ route("admin_order.edit_item") }}" 
                                            data-title="{{ trans('order.qty') }}"> 
                                            {{ $item->qty }}
                                        </a>
                                    </td>
                                    <td class="product_total item_id_{{ $item->id }}">{{ bc_currency_render_symbol($item->total_price,$order->currency)}}</td>
                                    <td class="product_tax"><a href="#" class="edit-item-detail" 
                                            data-value="{{ $item->tax }}" 
                                            data-name="tax" 
                                            data-type="number" min=0 
                                            data-pk="{{ $item->id }}" 
                                            data-url="{{ route("admin_order.edit_item") }}" 
                                            data-title="{{ trans('order.tax') }}"> 
                                            {{ $item->tax }}
                                        </a>
                                    </td>
                                    <td>
                                        <span  onclick="deleteItem({{ $item->id }});" class="btn btn-danger btn-xs" data-title="Delete">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            <tr id="add-item" class="not-print">
                                <td colspan="7">
                                    <button style="display: none; margin-right: 50px" type="button" class="btn btn-flat btn-success" id="add-item-button-save"  title="Save"><i class="fa fa-save"></i> {{ trans('admin.save') }}</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    @php
        if($order->balance == 0){
            $style = 'style="color:#0e9e33;font-weight:bold;"';
        }else
            if($order->balance < 0){
            $style = 'style="color:#ff2f00;font-weight:bold;"';
        }else{
            $style = 'style="font-weight:bold;"';
        }
    @endphp
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <table class="table tablesorter tablesorter-default">
                    @foreach ($dataTotal as $element)
                    @if ($element['code'] =='subtotal')
                    <tr>
                        <td  class="td-title-normal">{!! $element['title'] !!}:</td>
                        <td class="data-{{ $element['code'] }}">{{ bc_currency_format($element['value']) }}</td>
                    </tr>
                    @endif
                    @if ($element['code'] =='tax')
                    <tr>
                        <td  class="td-title-normal">{!! $element['title'] !!}:</td>
                        <td class="data-{{ $element['code'] }}">{{ bc_currency_format($element['value']) }}</td>
                    </tr>
                    @endif
                    @if ($element['code'] =='shipping')
                    <tr>
                        <td>{!! $element['title'] !!}:</td>
                        <td ><a href="#" class="updatePrice data-{{ $element['code'] }}"  data-name="{{ $element['code'] }}" data-type="text" data-pk="{{ $element['id'] }}" data-url="{{ route("admin_order.update") }}" data-title="{{ trans('order.shipping_price') }}">{{$element['value'] }}</a></td>
                    </tr>
                    @endif
                    @if ($element['code'] =='discount')
                    <tr>
                        <td>{!! $element['title'] !!}(-):</td>
                        <td><a href="#" class="updatePrice data-{{ $element['code'] }}" data-name="{{ $element['code'] }}" data-type="text" data-pk="{{ $element['id'] }}" data-url="{{ route("admin_order.update") }}" data-title="{{ trans('order.discount') }}">{{$element['value'] }}</a></td>
                    </tr>
                    @endif
                    @if ($element['code'] =='total')
                    <tr>
                        <td>{!! $element['title'] !!}:</td>
                        <td class="data-{{ $element['code'] }}">{{ bc_currency_format($element['value']) }}</td>
                    </tr>
                    @endif
                    @if ($element['code'] =='received')
                    <tr>
                        <td>{!! $element['title'] !!}(-):</td>
                        <td><a href="#" class="updatePrice data-{{ $element['code'] }}" data-name="{{ $element['code'] }}" data-type="text" data-pk="{{ $element['id'] }}" data-url="{{ route("admin_order.update") }}" data-title="{{ trans('order.received') }}">{{$element['value'] }}</a></td>
                    </tr>
                    @endif
                    @endforeach
                    <tr  {!! $style !!}  class="data-balance">
                    <td>{{ trans('order.balance') }}:</td>
                    <td>{{($order->balance === NULL)?bc_currency_format($order->total):bc_currency_format($order->balance) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header p-3 d-flex justify-content-between align-content-center">
                <h4 class="mb-0 align-self-center card-title">{{ trans('order.order_history') }}</h4>
            </div>
            {{-- <span><b>Agent:</b> {{ $order->user_agent }}</span>
            <span><b>IP:</b> {{ $order->ip }}</span> --}}
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    @if (count($order->history))
                    <table class="table tablesorter tablesorter-default" id="history">
                        <tr>
                            <th>{{ trans('order.history_staff') }}</th>
                            <th>{{ trans('order.history_content') }}</th>
                            <th>{{ trans('order.history_time') }}</th>
                        </tr>
                        @foreach ($order->history->sortKeysDesc()->all() as $history)
                        <tr>
                            <td>{{ \BlackCart\Core\Admin\Models\AdminUser::find($history['admin_id'])->name??'' }}</td>
                            <td>
                                <div class="history">{!! $history['content'] !!}</div>
                            </td>
                            <td>{{ $history['add_date'] }}</td>
                        </tr>
                        @endforeach
                    </table>
                    @endif
                </div>
                <!-- /.table-responsive -->
            </div>
        </div>
    </div>
</div>
@endsection


@push('css')
<style type="text/css">
.history{
  max-height: 150px;
  width: 60%;
  overflow-y: auto;
}
.td-title{
  width: 35%;
  font-weight: bold;
}
.td-title-normal{
  width: 35%;
}
.product_qty{
  width: 80px;
  text-align: right;
}
.product_price,.product_total{
  width: 120px;
  text-align: right;
}
.bs-searchbox .form-control{
    border-radius: 4px;
    border-bottom-color: #2b3553; 
    height: 30px;
}
.bs-searchbox .form-control:focus{
    color: #2b3553!important;
}
</style>
<!-- Ediable -->
<link rel="stylesheet" href="{{ asset('admin/plugin/bootstrap-editable.css')}}">
@endpush

@push('scripts')
{{-- //Pjax --}}
<script src="{{ asset('admin/plugin/jquery.pjax.js')}}"></script>

<!-- Ediable -->
<script src="{{ asset('admin/plugin/bootstrap-editable.min.js')}}"></script>



<script type="text/javascript">

function update_total(e){
    node = e.closest('tr');
    var qty = node.find('.add_qty').eq(0).val();
    var price = node.find('.add_price').eq(0).val();
    node.find('.add_total').eq(0).val(qty*price);
}


//Add item
    function selectProduct(element){
        node = element.closest('tr');
        var id = parseInt(node.find('option:selected').eq(0).val());
        if(id == 0){
            node.find('.add_sku').val('');
            node.find('.add_qty').eq(0).val('');
            node.find('.add_price').eq(0).val('');
            node.find('.add_attr').html('');
            node.find('.add_tax').html('');
        }else{
            $.ajax({
                url : '{{ bc_route_admin('admin_order.product_info') }}',
                type : "get",
                dateType:"application/json; charset=utf-8",
                data : {
                     id : id,
                     order_id : {{ $order->id }},
                },
            beforeSend: function(){
                $('#loading').show();
            },
            success: function(returnedData){
                node.find('.add_sku').val(returnedData.sku);
                node.find('.add_qty').eq(0).val(1);
                node.find('.add_price').eq(0).val(returnedData.price_final * {!! ($order->exchange_rate)??1 !!});
                node.find('.add_total').eq(0).val(returnedData.price_final * {!! ($order->exchange_rate)??1 !!});
                node.find('.add_attr').eq(0).html(returnedData.renderAttDetails);
                node.find('.add_tax').eq(0).html(returnedData.tax);
                $('#loading').hide();
                }
            });
        }

    }
$('#add-item-button').click(function() {
  var html = '@include($templatePathAdmin.'Component.OrdProdList',['products' => $products])';
  $('#add-item').before(html);
  $('#add-item-button-save').show();
  $('.selectpicker').selectpicker();
});

$('#add-item-button-save').click(function(event) {
    $('#add-item-button').prop('disabled', true);
    $('#add-item-button-save').button('loading');
    $.ajax({
        url:'{{ route("admin_order.add_item") }}',
        type:'post',
        dataType:'json',
        data:$('form#form-add-item').serialize(),
        beforeSend: function(){
            $('#loading').show();
        },
        success: function(result){
          $('#loading').hide();
            if(parseInt(result.error) ==0){
                location.reload();
            }else{
              alertMsg('error', result.msg);
            }
        }
    });
});

//End add item
//

$(document).ready(function() {
  all_editable();
});

function all_editable(){
    $.fn.editable.defaults.params = function (params) {
        params._token = "{{ csrf_token() }}";
        return params;
    };
    $.fn.editableform.buttons ='<div class="btn-group">'+
                                    '<button type="submit" class="btn btn-success btn-sm editable-submit">' +
                                        '<i class="fa fa-fw fa-check"></i>' +
                                    '</button>' +
                                    '<button type="button" class="btn btn-danger btn-sm editable-cancel">' +
                                        '<i class="fa fa-fw fa-times"></i>' +
                                    '</button>'+
                                '</div>';

    $('.updateInfo').editable({
      success: function(response) {
        if(response.error ==0){
          alertMsg('success', response.msg);
        } else {
          alertMsg('error', response.msg);
        }
    }
    });

    $(".updatePrice").on("shown", function(e, editable) {
      var value = $(this).text().replace(/,/g, "");
      editable.input.$input.val(parseInt(value));
    });

    $('.updateStatus').editable({
        validate: function(value) {
            if (value == '') {
                return '{{  trans('admin.not_empty') }}';
            }
        },
        success: function(response) {
          if(response.error ==0){
            alertMsg('success', response.msg);
          } else {
            alertMsg('error', response.msg);
          }
      }
    });

    $('.updateInfoRequired').editable({
        validate: function(value) {
            if (value == '') {
                return '{{  trans('admin.not_empty') }}';
            }
        },
        success: function(response,newValue) {
          console.log(response.msg);
          if(response.error == 0){
            alertMsg('success', response.msg);
          } else {
            alertMsg('error', response.msg);
          }
      }
    });


    $('.edit-item-detail').editable({
        ajaxOptions: {
        type: 'post',
        dataType: 'json'
        },
        validate: function(value) {
          if (value == '') {
              return '{{  trans('admin.not_empty') }}';
          }
          if (!$.isNumeric(value)) {
              return '{{  trans('admin.only_numeric') }}';
          }
        },
        success: function(response,newValue) {
            if(response.error ==0){
                $('.data-shipping').html(response.detail.shipping);
                $('.data-received').html(response.detail.received);
                $('.data-subtotal').html(response.detail.subtotal);
                $('.data-tax').html(response.detail.tax);
                $('.data-total').html(response.detail.total);
                $('.data-shipping').html(response.detail.shipping);
                $('.data-discount').html(response.detail.discount);
                $('.item_id_'+response.detail.item_id).html(response.detail.item_total_price);
                var objblance = $('.data-balance').eq(0);
                objblance.before(response.detail.balance);
                objblance.remove();
                alertMsg('success', response.msg);
            } else {
              alertMsg('error', response.msg);
            }
        }

    });

    $('.updatePrice').editable({
        ajaxOptions: {
        type: 'post',
        dataType: 'json'
        },
        validate: function(value) {
          if (value == '') {
              return '{{  trans('admin.not_empty') }}';
          }
          if (!$.isNumeric(value)) {
              return '{{  trans('admin.only_numeric') }}';
          }
       },

        success: function(response, newValue) {
              if(response.error ==0){
                  $('.data-shipping').html(response.detail.shipping);
                  $('.data-received').html(response.detail.received);
                  $('.data-subtotal').html(response.detail.subtotal);
                  $('.data-tax').html(response.detail.tax);
                  $('.data-total').html(response.detail.total);
                  $('.data-shipping').html(response.detail.shipping);
                  $('.data-discount').html(response.detail.discount);
                  var objblance = $('.data-balance').eq(0);
                  objblance.before(response.detail.balance);
                  objblance.remove();
                  alertMsg('success', response.msg);
              } else {
                alertMsg('error', response.msg);
              }
      }
    });
}


{{-- sweetalert2 --}}
function deleteItem(id){
  Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-success',
      cancelButton: 'btn btn-danger'
    },
    buttonsStyling: true,
  }).fire({
    title: '{{ trans('admin.confirm_delete') }}',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonText: '{{ trans('admin.confirm_delete_yes') }}',
    confirmButtonColor: "#DD6B55",
    cancelButtonText: '{{ trans('admin.confirm_delete_no') }}',
    reverseButtons: true,

    preConfirm: function() {
        return new Promise(function(resolve) {
            $.ajax({
                method: 'POST',
                url: '{{ route("admin_order.delete_item") }}',
                data: {
                  'pId':id,
                    _token: '{{ csrf_token() }}',
                },
                success: function (response) {
                  if(response.error ==0){
                    location.reload();
                    alertMsg('success', response.msg);
                } else {
                  alertMsg('error', response.msg);
                }
                  
                }
            });
        });
    }

  }).then((result) => {
    if (result.value) {
      alertMsg('success', '{{ trans('admin.confirm_delete_deleted_msg') }}', '{{ trans('admin.confirm_delete_deleted') }}' );
    } else if (
      // Read more about handling dismissals
      result.dismiss === Swal.DismissReason.cancel
    ) {
      // swalWithBootstrapButtons.fire(
      //   'Cancelled',
      //   'Your imaginary file is safe :)',
      //   'error'
      // )
    }
  })
}
{{--/ sweetalert2 --}}


  $(document).ready(function(){
  // does current browser support PJAX
    if ($.support.pjax) {
      $.pjax.defaults.timeout = 2000; // time in milliseconds
    }

  });

  function order_print(){
    $('.not-print').hide();
    window.print();
    $('.not-print').show();
  }
</script>

@endpush
