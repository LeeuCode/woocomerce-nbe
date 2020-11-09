<?php

$this->form_fields = apply_filters( 'wc_nbe_form_fields', array(
    'enabled' => array(
        'title'   => __( 'Enable/Disable', 'lc' ),
        'type'    => 'checkbox',
        'label'   => __( 'Enable Offline Payment', 'lc' ),
        'default' => 'no'
    ),
    'title' => array(
        'title'       => 'Title',
        'type'        => 'text',
        'description' => __('This controls the title which the user sees during checkout.', 'lc'),
        'default'     => __('NBE', 'lc'),
        'desc_tip'    => true,
    ),
    'description' => array(
        'title'       => 'Description',
        'type'        => 'textarea',
        'description' => __('This controls the description which the user sees during checkout.', 'lc'),
        'default'     => __('Pay with your credit card via our super-cool payment gateway.', 'lc'),
    ),
    'testmode' => array(
        'title'       => 'Test mode',
        'label'       => 'Enable Test Mode',
        'type'        => 'checkbox',
        'description' => 'Place the payment gateway in test mode using test API keys.',
        'default'     => 'yes',
        'desc_tip'    => true,
    ),
) );