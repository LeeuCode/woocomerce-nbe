<?php 

class WC_NBE extends WC_Payment_Gateway 
{
    protected $testmode = false;

    public function __construct()
    {
        $this->order_button_text = __( 'Proceed to NBE', 'woocommerce' );
        $this->method_title      = __( 'PayPal Standard', 'woocommerce' );

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
        $this->icon = apply_filters( 'wc_nbe_icon', plugins_url( '../assets/imgs/Ahly Shopping LOGO.PNG', __FILE__ ));
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

    public function payment_fields() {
 
        // ok, let's display some description before the payment form
        if ( $this->description ) {
            // you can instructions for test mode, I mean test card numbers etc.
            if ( $this->testmode ) {
                $this->description .= ' TEST MODE ENABLED. In test mode, you can use the card numbers listed in <a href="#" target="_blank" rel="noopener noreferrer">documentation</a>.';
                $this->description  = trim( $this->description );
            }
            // display the description with <p> tags etc.
            echo wpautop( wp_kses_post( $this->description ) );
        }
     
        // I will echo() the form, but you can close PHP tags and print it directly in HTML
        echo '<fieldset id="wc-' . esc_attr( $this->id ) . '-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">';
     
        // Add this action hook if you want your custom payment gateway to support it
        do_action( 'woocommerce_credit_card_form_start', $this->id );
     
        // I recommend to use inique IDs, because other gateways could already use #ccNo, #expdate, #cvc
        echo '<input type="hidden" readonly="readonly" name="method" value="PUT" size="20" maxlength="80">
        <div class="form-row form-row-wide"><label>Card Number <span class="required">*</span></label>
            <input name="card_number" id="misha_ccNo" type="text" autocomplete="off">
            </div>
            <div class="form-row form-row-first">
                <label>Expiry Date <span class="required">*</span></label>
                <input name="expiry_date" id="misha_expdate" type="text" autocomplete="off" placeholder="MM / YY">
            </div>
            <div class="form-row form-row-last">
                <label>Card Code (CVC) <span class="required">*</span></label>
                <input name="cvc" id="misha_cvv" type="password" autocomplete="off" placeholder="CVC">
            </div>
            <div class="clear"></div>';
     
        do_action( 'woocommerce_credit_card_form_end', $this->id );
     
        echo '<div class="clear"></div></fieldset>';
    }

    /**
	 * Process the payment and return the result.
	 *
	 * @param int $order_id Order ID.
	 * @return array
	 */
	public function process_payment( $order_id ) {

        $order = wc_get_order( $order_id );
        // Errors
        $errors = array();

        // Check card number not is empty.
        if ( isset($_POST['card_number']) && empty($_POST['card_number']) ) {
            $errors[] = "<li>". __('The Card Number field is required','lc') ."</li>";
        }

        // Check expiry date not is empty.
        if ( isset($_POST['expiry_date']) && empty($_POST['expiry_date']) ) {
            $errors[] = "<li>". __('The Expiry Date field is required', 'lc') ."</li>";
        }

        // Check cvc not is empty.
        if ( isset($_POST['cvc']) && empty($_POST['cvc']) ) {
            $errors[] = "<li>". __('The CVC field is required', 'lc') ."</li>";
        }

        if (count($errors) > 0) {
            $error = implode(' ', $errors);
            wc_add_notice('<ul>'. $error .'</ul>', 'error' );
            return false;
        }

		if ( $order->get_total() > 0 ) {
			// Mark as processing or on-hold (payment won't be taken until delivery).
			// $order->update_status( apply_filters( 'woocommerce_nbe_process_payment_order_status', $order->has_downloadable_item() ? 'on-hold' : 'processing', $order ), __( 'Payment to be made upon delivery.', 'woocommerce' ) );
            $orderStauts = $this->nbe_process_payment($order);
        } else {
			$order->payment_complete();
		}

        if ($orderStauts == true) {
            // Remove cart.
            WC()->cart->empty_cart();
    
            // Return thankyou redirect.
            return array(
                'result'   => 'success',
                'redirect' => $this->get_return_url( $order ),
            );  
        }
    }
    
    public function nbe_process_payment($order)
    {
        require plugin_dir_path(__FILE__).'/nbe_api/process.php';
        
        return false;
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