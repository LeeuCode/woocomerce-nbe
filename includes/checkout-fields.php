<?php 

// woocommerce_form_field(
//     'card_number',
//     array(
//         'type' => 'text',
//         'label' => __('Card Number', 'lc'),
//         'class' => array('form-row-wide', 'form-row'),
//         'required' =>true
//     )
// );

// woocommerce_form_field(
//     'month',
//     array(
//         'type' => 'number',
//         'label' => __('Month', 'lc'),
//         'class' => array('form-row-wide', 'form-row'),
//         'required' =>true
//     )
// );

// // I will echo() the form, but you can close PHP tags and print it directly in HTML
// echo '<fieldset id="wc-' . esc_attr( $this->id ) . '-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">';
 
// // Add this action hook if you want your custom payment gateway to support it
// do_action( 'woocommerce_credit_card_form_start', $this->id );

// // I recommend to use inique IDs, because other gateways could already use #ccNo, #expdate, #cvc
// echo '<div class="form-row form-row-wide"><label>Card Number <span class="required">*</span></label>
//     <input id="misha_ccNo" type="text" autocomplete="off">
//     </div>
//     <div class="form-row form-row-first">
//         <label>Expiry Date <span class="required">*</span></label>
//         <input id="misha_expdate" type="text" autocomplete="off" placeholder="MM / YY">
//     </div>
//     <div class="form-row form-row-last">
//         <label>Card Code (CVC) <span class="required">*</span></label>
//         <input id="misha_cvv" type="password" autocomplete="off" placeholder="CVC">
//     </div>
//     <div class="clear"></div>';

// do_action( 'woocommerce_credit_card_form_end', $this->id );

// echo '<div class="clear"></div></fieldset>';