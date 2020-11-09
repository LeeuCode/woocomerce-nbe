<?php 

class WC_NBE extends WC_Payment_Gateway 
{
    public function __construct()
    {
        /*==== Setup properties ====*/
        $this->setup_properties();

        /*==== This action hook saves the settings ====*/
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, 
        array( $this, 'process_admin_options' ) );

        // Order status.
        add_action( 'woocommerce_payment_complete_order_status', array( $this, 'change_payment_complete_order_status' ) , 10, 3);

        // Thank you page
        add_action( 'woocommerce_thankyou_bacs', array( $this, 'thankyou_page' ) );

        // Customer Emails.
        add_action( 'woocommerce_email_before_order_table',
            array( $this, 'email_instructions' ), 10, 3 );
    }

    protected function setup_properties()
    {
        $this->id = 'nbe';
        $this->icon = apply_filters( 'wc_nbe_icon', plugins_url( '../assets/imgs/NBE-logo.svg', __FILE__ ));
        $this->method_title = __('National Bank of Egypt', 'lc');
        $this->method_description = __('The woocommerce payment gateway for the National Bank of Egypt', 'lc');
        $this->init_form_fields();
        
        /*===== Load the settings ====*/
        $this->init_settings();
        $this->title = $this->get_option( 'title' );
        $this->description = $this->get_option( 'description' );
        $this->enabled = $this->get_option( 'enabled' );
    }

    /*==== Initialize Gateway Settings Form Fields ====*/
    public function init_form_fields()
    {
        require 'init_form_fields.php';
    }

    /**
	 * Process the payment and return the result.
	 *
	 * @param int $order_id Order ID.
	 * @return array
	 */
	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );

		if ( $order->get_total() > 0 ) {
			// Mark as processing or on-hold (payment won't be taken until delivery).
			$order->update_status( apply_filters( 'woocommerce_nbe_process_payment_order_status', $order->has_downloadable_item() ? 'on-hold' : 'processing', $order ), __( 'Payment to be made upon delivery.', 'woocommerce' ) );
		} else {
			$order->payment_complete();
		}

		// Remove cart.
		WC()->cart->empty_cart();

		// Return thankyou redirect.
		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url( $order ),
		);
	}

    /*==== Output for the order received page ====*/
    public function thankyou_page() {
        if ( $this->instructions ) {
            echo wpautop( wptexturize( $this->instructions ) );
        }
    }

    /**
     * Add content to the WC emails.
     *
     * @access public
     * @param WC_Order $order
     * @param bool $sent_to_admin
     * @param bool $plain_text
     */
    public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
            
        if ( $this->instructions && ! $sent_to_admin && 'offline' === $order->payment_method && $order->has_status( 'on-hold' ) ) {
            echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
        }
    }

    public function change_payment_complete_order_status($staus, $order_id = 0, $order = false)
    {
        if ( $order && 'nbe' == $order->get_payment_method() ) {
            $staus = 'complate';
        }
        return $staus;
    }

} // end \WC_NBE class