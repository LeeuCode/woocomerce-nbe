<?php

add_filter( 'woocommerce_gateway_description', 'wc_nb_checkout_description_fields',20 ,2);

function wc_nb_checkout_description_fields($description, $payment_id)
{
    if ('nbe' != $payment_id) {
        return $description;
    }

    ob_start();
        require 'checkout-fields.php';
        // echo '<img src="'.plugins_url( '../assets/imgs/NBE-logo.svg', __FILE__ ).'" alt="" srcset="">';
    $description .= ob_get_clean();


    return $description;
}