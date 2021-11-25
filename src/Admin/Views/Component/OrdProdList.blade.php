@php
$htmlSelectProduct = '<tr>
    <td>
        <select onChange="selectProduct($(this));" data-live-search="true" class="add_id form-control selectpicker" name="add_id[]">
            <option value="0">'.trans('order.select_product').'</option>';
            if(count($products)){
                foreach ($products as $pId => $product){
                    $htmlSelectProduct .= '<option value="'.$pId.'" >'.$product['name'].' ('.$product['sku'].')</option>';
                }
            }
$htmlSelectProduct .= '</select>
        <span class="add_attr"></span>
    </td>
    <td><input type="text" disabled class="add_sku form-control"  value=""></td>
    <td><input onChange="update_total($(this));" type="number" min="0" class="add_price form-control" name="add_price[]" value="0"></td>
    <td><input onChange="update_total($(this));" type="number" min="0" class="add_qty form-control" name="add_qty[]" value="0"></td>
    <td><input type="number" disabled class="add_total form-control" value="0"></td>
    <td><input  type="number" min="0" class="add_tax form-control" name="add_tax[]" value="0"></td>
    <td>
        <button onClick="$(this).parent().parent().remove();" class="btn btn-danger btn-md btn-flat" data-title="Delete">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
    </td>
</tr>
<tr>
</tr>';
    $htmlSelectProduct = str_replace("\n", '', $htmlSelectProduct);
    $htmlSelectProduct = str_replace("\t", '', $htmlSelectProduct);
    $htmlSelectProduct = str_replace("\r", '', $htmlSelectProduct);
    $htmlSelectProduct = str_replace("'", '"', $htmlSelectProduct);
    echo $htmlSelectProduct;
@endphp