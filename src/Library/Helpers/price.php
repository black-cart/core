<?php 

/*
    Return price with tax
*/
if (!function_exists('bc_tax_price')) {
    function bc_tax_price($price, $tax)
    {
        return floor($price * (100 + $tax) /100);
    }
}

/**
 * Render html option price
 *
 * @param   string $arrtribute  format: attribute-name__value-option-price
 * @param   string $currency    code currency
 * @param   string  $rate        rate exchange
 * @param   string               [ description]
 *
 * @return  [type]             [return description]
 */
if (!function_exists('bc_render_option_price')) {
    function bc_render_option_price($arrtribute, $currency = null, $rate = null,$qty = '')
    {
        $html = '';
        $tmpAtt = explode('__', $arrtribute);
        $add_price = $tmpAtt[1] ?? 0;
        if ($add_price) {
            if ($qty) {
                $qty = 'x'.$qty;
            }
            $html = $tmpAtt[0].'<span class="option_price"> ('.bc_currency_render($add_price,$currency, $rate).$qty.')</span>';
        } else {
            $html = $tmpAtt[0];
        }
        return $html;
    }
}