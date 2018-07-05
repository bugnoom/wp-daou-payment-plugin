<?php
ini_set('display_errors','yes');
/**
 * Plugin Name: DAOU Payment Gateway
 * Plugin URI: https://www.yoonthai.com
 * Description:  gateway to create another manual  method; can be used for testing as well.
 * Author: Bugnoom
 * Author URI: https://www.yoonthai.com
 * Version: 1.0.0
 * Text Domain: daou-payment-gateway
 * Domain Path: /i18n/languages/
 *
 * * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * 
 *
 * @class       Daou_Gateway
 * @extends WC_Payment_Gateway
 * @version     1.0.0
 * @package     WooCommerce/Classes/Payment
 * @author      Bugnoom
 */
defined( 'ABSPATH' ) or exit;

// check woocommerce is active
if(!in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ))){
    echo "No pugin"; return;
} 


/**
 * add The gateway to WC Avaliable Gateways
 * 
 * @since 1.0.0
 * @param array $gateways all available WC gateways
 * @return array $gateways All WC gateway and Daou Payment
 */
function wc_daou_payment_add_to_gateways($gateways){
    $gateways[] = 'WC_Daou_Payment';
    return $gateways;
}
add_filter( 'woocommerce_payment_gateways','wc_daou_payment_add_to_gateways');

/**
 * Adds plugin page links
 * 
 * @since 1.0.0
 * @param array $links all plugin links
 * @return array $links all plugin links and Daou Payment Link
 */
function wc_daou_payment_plugin_links($links){
    $plugin_links = array(
        '<a href="'.admin_url('admin.php?page=wc-settings&tab=checkout&section=daou_payment').'">'.__('Configure','daou-payment-gateway').'</a>'
    );

    return array_merge($plugin_links,$links);
}
add_filter( 'plugin_action_links_'.plugin_basename( __FILE__ ),'wc_daou_payment_plugin_links' );

/**
 * Daou Payment Gateway
 * 
 * Provide for support DaouPayment Gateway from Koria
 * 
 * @class   WC_Daou_Payment
 * @extends WC_Payment_Gateway
 * @version 1.0.0
 * @package Woocommerce/Classes/Payment
 * @author  bugnoom
 */
add_action( 'plugins_loaded', 'wc_daou_payment_init',11 );

function wc_daou_payment_init(){
    Class WC_Daou_Payment extends WC_Payment_Gateway{
        /**
         * Constructior for the gateway.
         */
        public function __construct(){
            $this->id           = 'daou_payment';
            $this->icon         = apply_filters( 'woocommerce_offline_icon','' );
            $this->has_fields   = false;
            $this->method_title   = __('DaouPayment','daou-payment-gateway');
            $this->method_description   = __('Support DAOUPAYMENT Provider from korian','daou-payment-gateway');

            //Load the settings.
            $this->init_form_fields();
            $this->init_settings();

            //Define user set Variables
            $this->title        = $this->get_option('title');
            $this->description  = $this->get_option('description');
            $this->instructions = $this->get_option('instructions', $this->description);

            //Actions
            add_action('woocommerce_update_options_payment_gateways_'.$this->id, array($this, 'process_admin_options'));
            /* add_action( 'woocommerce_thankyou_'.$this->id, array($this,'thankyou_page'));
            add_action( 'woocommerce_api_' . $this->id.'_callback', array( $this, 'process_wspay_response' ) ); */

            add_action('woocommerce_receipt_daou_payment', array($this,'pay_for_order'));

            //Custome Emails
            add_action('woocommerce_email_before_order_table',array($this,'email_instructions'),10,3);
            
        }

        /**
         * Initialize Gateway Settings Form Fields
         */
        public function init_form_fields(){
            $this->form_fields = apply_filters( 'daou_payment_form_field',array(
                'enabled' => array(
                    'title'     => __('Enable/Disable','daou-payment-gateway'),
                    'type'      => 'checkbox',
                    'label'     => __('Enable Daou payment', 'daou-payment-gateway'),
                    'default'   => 'yes'
                ),

                'enviroment' => array(
                    'title'     => __('DAOUPAY Test Mode?','daou-payment-gateway'),
                    'label'     => __('Enable Payment in Test Mode','daou-payment-gateway'),
                    'type'      => 'checkbox',
                    'description' => __('This is the test mode of gateway.','daou-payment-gateway'),
                    'default'   => 'no'
                ),
                
                'title' => array(
                    'title'     => __('Title','daou-payment-gateway'),
                    'type'      => 'text',
                    'description' => __('This Controls the title for the payemnt method the customer sees on your checkout.', 'daou-payment-gateway'),
                    'label' => __('his controls the title which the user sees during checkout page.','daou-payment-gateway'),
                    'default'   => __('Daou Payment Gateway','daou-payment-gateway'),
                    'desc_tip'  => true,
                ),
                
                'description' => array(
                    'title'     =>__('Description','daou-payment-gateway'),
                    'type'      => 'textarea',
                    'description' => __('Payment method description that the customer will see on your checkout.','daou-payment-gateway'),
                    'label'     => __('This controls the description which the user sees during checkout page.','daou-payment-gateway'),
                    'default'   => __('Please remit payment to Store Name upon pickup or delivery.','daou-payment-gateway'),
                    'desc_tip'  => true,
                ),

                'instructions' => array(
                    'title'     => __('Instructions','daou-payment-gateway'),
                    'type'      => 'textarea',
                    'description' => __('Instructions that will be added to the thank you page and emails.','daou-payment-gateway'),
                    'default'   => '',
                    'desc_tip'  => true,
                ),

                
            ) );
        }
            /**
             * Output for the order recieved page.
             */
       /*  public function thankyou_page(){
            if($this->instructions){
                echo wpautop( wptexturize( $this->instructions));
            }
        }
 */
        /**
         * Add Content to the WC Emails.
         * 
         * @access public
         * @param WC_Order $order
         * @param bool $sent_to_admin
         * @param bool $plain_text
         */
       /*  public function email_instructions($orcer,$sent_to_admin,$plain_text = false){
            if($this->instructions && ! $sent_to_admin && $this->id === $order->get_payment_method && $order->has_status('on-hold')){
                echo wpautop( wptexturize( $this->instructions)) . PHP_EOL;
            }
        }
 */
        /**
         * Process the payment and return the result
         * 
         * @param int $order_id
         * @return array
         */
        public function process_payment($order_id){
           global $woocommerce;
            $order = wc_get_order( $order_id );

           //F

              //check for Testmode ?
              $enviroment = ($this->enviroment == 'yes')? 'TRUE' : 'FALSE';

              //Fixx URL to send to DAOU PAYMENT Provider
              //$enviroment_url = 'https://ssltest.kiwoompay.co.kr/card/DaouCardMng.jsp';
               $enviroment_url = ("FALSE" == $enviroment)? ' https://ssl.kiwoompay.co.kr/card/DaouCardMng.jsp' : ' https://ssltest.kiwoompay.co.kr/card/DaouCardMng.jsp';
  
 
             // $product_amount = $this->format_amount_subunit( $order->get_total(), $order->get_order_currency() );
             $product_amount = wc_trim_zeros($order->get_total());
              $product_name = "";
              foreach($order->get_items() as $item) {
                 $product_name .= $item['name'].",";
             
             }
             $user = $order->get_user();
             $user_id = $order->get_user_id();
             $order_number = trim(str_replace('#', '', $order->get_order_number()));
 
             //echo '<p>' . __( 'Redirecting to payment provider.', 'txtdomain' ) . '</p>';
             // add a note to show order has been placed and the user redirected
             $order->add_order_note( __( 'Order placed and user redirected to DAOU PAYMENT.', 'daou-payment-gateway' ) );
             // update the status of the order should need be
             $order->update_status( 'on-hold', __( 'Awaiting payment.', 'daou-payment-gateway' ) );
             // remember to empty the cart of the user
             WC()->cart->empty_cart();

             //create parameter send to endpoint payment
             $paramdetail = array(
                'CPID'          => "YOU KEY FROM DAOU PROVIDER", //input you key from DAOU Payment
                'ORDERNO'       => $order_number,
                'AMOUNT'        => $product_amount,
                "PRODUCTNAME"   => $product_name,
                "USERID"        => $user_id,
                "USERNAME"      => $user->user_login,
                'envurl'        => $this->enviroment,
                'success_url'   => $this->get_return_url($order)

             );
             
             $param = http_build_query($paramdetail);

            return array(
                'result'    => 'success',
                'redirect' => 'https://www.yoonthai.com/webservices/endpoint.php?'.$param
            );
        }

       // here, prepare your form and submit it to the required URL
        public function pay_for_order( $order_id ) {
            $order = wc_get_order( $order_id ); 
 
        }


    } //end \WC_Daou_Payment
    
}

