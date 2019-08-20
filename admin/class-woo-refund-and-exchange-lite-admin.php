<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.makewebbetter.com
 * @since      1.0.0
 *
 * @package    Woo_Refund_And_Exchange_Lite
 * @subpackage Woo_Refund_And_Exchange_Lite/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Refund_And_Exchange_Lite
 * @subpackage Woo_Refund_And_Exchange_Lite/admin
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Woo_Refund_And_Exchange_Lite_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Refund_And_Exchange_Lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Refund_And_Exchange_Lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-refund-and-exchange-lite-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'mwb-rma-select2-css', plugin_dir_url( __FILE__ ) . 'css/mwb_ps-select2.min.css', array(), $this->version, 'all' );
		wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
		wp_enqueue_style( 'woocommerce_admin_menu_styles' );
		wp_enqueue_style( 'woocommerce_admin_styles' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Refund_And_Exchange_Lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Refund_And_Exchange_Lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


		wp_register_script( 'woocommerce_admin', WC()->plugin_url() . '/assets/js/admin/woocommerce_admin.js', array( 'jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip' ), WC_VERSION );
		$locale = localeconv();
		$decimal = isset( $locale['decimal_point'] ) ? $locale['decimal_point'] : '.';

		$params = array(
			/* translators: %s: decimal */
			'i18n_decimal_error' => sprintf( __( 'Please enter in decimal (%s) format without thousand separators.', 'woocommerce' ), $decimal ),
			/* translators: %s: price decimal separator */
			'i18n_mon_decimal_error' => sprintf( __( 'Please enter in monetary decimal (%s) format without thousand separators and currency symbols.', 'woocommerce' ), wc_get_price_decimal_separator() ),
			'i18n_country_iso_error' => __( 'Please enter in country code with two capital letters.', 'woocommerce' ),
			'i18_sale_less_than_regular_error' => __( 'Please enter in a value less than the regular price.', 'woocommerce' ),
			'decimal_point' => $decimal,
			'mon_decimal_point' => wc_get_price_decimal_separator(),
			'strings' => array(
				'import_products' => __( 'Import', 'woocommerce' ),
				'export_products' => __( 'Export', 'woocommerce' ),
			),
			'urls' => array(
				'import_products' => esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_importer' ) ),
				'export_products' => esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_exporter' ) ),
			),
		);

		wp_localize_script( 'woocommerce_admin', 'woocommerce_admin', $params );
		wp_enqueue_script( 'woocommerce_admin' );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-refund-and-exchange-lite-admin.js', array( 'jquery' ), $this->version, false );

		$ajax_nonce = wp_create_nonce( "mwb-rma-ajax-security-string" );
		$translation_array = array(
			'remove'	=>	__( 'Remove' , 'woo-refund-and-exchange-lite'),
			'mwb_rma_nonce' => $ajax_nonce,
			'ajaxurl' => admin_url('admin-ajax.php'),
		);
		wp_localize_script(  $this->plugin_name, 'global_mwb_rma', $translation_array );
		wp_enqueue_script( "mwb-rma-select2-js", plugin_dir_url( __FILE__ ) . 'js/mwb_ps-select2.min.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add new admin menu under woocommerce
	 * @param $array 
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public static function mwb_add_admin_menu( $array ) {
		add_submenu_page( 'woocommerce',
			__( 'RMA Setting' , 'woo-refund-and-exchange-lite'), 
			__( 'RMA Setting' , 'woo-refund-and-exchange-lite'),
			'manage_options',
			__( 'mwb-rma-setting','woo-refund-and-exchange-lite'),
			array( $this , 'mwb_rma_setting_page_callback')
		);

	}

	/**
	 * Include RMA setting page
	 * @param $array 
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_rma_setting_page_callback(){
		include_once MWB_RMA_DIR_PATH.'admin/partials/woo-refund-and-exchange-lite-admin-display.php';
	}

	/**
	 * Add new return meta box on order edit page
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_rma_add_order_edit_meta_box(){
		$mwb_rma_refund_settings = get_option( 'mwb_rma_refund_settings', array() );
		if(isset($mwb_rma_refund_settings) && !empty($mwb_rma_refund_settings) && is_array($mwb_rma_refund_settings)){
			$mwb_rma_return_enable = isset($mwb_rma_refund_settings['mwb_rma_return_enable'])?$mwb_rma_refund_settings['mwb_rma_return_enable']:'';
			if(isset($mwb_rma_return_enable) && $mwb_rma_return_enable == 'on'){
				add_meta_box('mwb_rma_order_refund', 
					__('Refund Requested Products','woo-refund-and-exchange-lite'),
					array($this, 'mwb_rma_order_return'),
					'shop_order');
				
			}
		}
	}

	/**
	 * This function is metabox template for Refund order product
	 * 
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 * @param unknown $order
	*/
	public function mwb_rma_order_return()
	{
		global $post, $thepostid, $theorder;
		include_once MWB_RMA_DIR_PATH.'admin/partials/templates/mwb-rma-return-product-meta.php';
	}

	/**
	 * This function is to add custom order status for return 
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */

	public function mwb_rma_register_custom_order_status()
	{
		register_post_status( 'wc-refund-requested', array(
			'label'                     => 'Refund Requested',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Refund Requested <span class="count">(%s)</span>', 'Refund Requested <span class="count">(%s)</span>' )
			) );

		register_post_status( 'wc-refund-approved', array(
			'label'                     => 'Refund Approved',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Refund Approved <span class="count">(%s)</span>', 'Refund Approved <span class="count">(%s)</span>' )
			) );

		register_post_status( 'wc-refund-cancelled', array(
			'label'                     => 'Refund Cancelled',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Refund Cancelled <span class="count">(%s)</span>', 'Refund Cancelled <span class="count">(%s)</span>' )
			) );
	}

	/**
	 * This function is to register custom order status
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 * @param unknown $mwb_rma_order_statuses
	 * @return multitype:string unknown
	 */

	public function mwb_rma_add_custom_order_status($mwb_rma_order_statuses)
	{
		$mwb_rma_new_order_statuses = array();
		foreach ( $mwb_rma_order_statuses as $mwb_rma_key => $mwb_rma_status ) {

			$mwb_rma_new_order_statuses[ $mwb_rma_key ] = $mwb_rma_status;

			if ( 'wc-completed' === $mwb_rma_key ) {
				$mwb_rma_new_order_statuses['wc-refund-requested'] = __('Refund Requested','woo-refund-and-exchange-lite');
				$mwb_rma_new_order_statuses['wc-refund-approved']  = __('Refund Approved','woo-refund-and-exchange-lite');
				$mwb_rma_new_order_statuses['wc-refund-cancelled'] = __('Refund Cancelled','woo-refund-and-exchange-lite');
			}
		}
		return $mwb_rma_new_order_statuses;	
	}

	/**
	 * This function is to process approve return request
	 * 
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */

	public function mwb_rma_return_req_approve_callback(){

		$check_ajax = check_ajax_referer( 'mwb-rma-ajax-security-string', 'security_check' );

		if ( $check_ajax ) {
			if(current_user_can('mwb-rma-refund-approve'))
			{
				$orderid =  sanitize_text_field($_POST['orderid']);
				$date = sanitize_text_field($_POST['date']);
				$products = get_post_meta($orderid, 'mwb_rma_return_request_product', true);

				//Fetch the return request product
				if(isset($products) && !empty($products))
				{
					foreach($products as $date=>$product)
					{
						if($product['status'] == 'pending')
						{
							$product_datas = $product['products'];
							$products[$date]['status'] = 'complete';
							$approvdate = date("d-m-Y");
							$products[$date]['approve_date'] = $approvdate;
							break;
						}
					}
				}

				 //Update the status
				update_post_meta($orderid, 'mwb_rma_return_request_product', $products);

				$request_files = get_post_meta($orderid, 'mwb_rma_return_attachment', true);

				if(isset($request_files) && !empty($request_files))
				{
					foreach($request_files as $date=>$request_file)
					{
						if($request_file['status'] == 'pending')
						{
							$request_files[$date]['status'] = 'complete';
							break;
						}
					}
				}

				//Update the status
				update_post_meta($orderid, 'mwb_rma_return_attachment', $request_files);


				$mwb_rma_mail_basic_settings = get_option('mwb_rma_mail_basic_settings',array());
				$mwb_rma_mail_refund_settings = get_option('mwb_rma_mail_refund_settings',array());

				$order = new WC_Order($orderid);
				$fmail =  isset($mwb_rma_mail_basic_settings['mwb_rma_mail_from_email'])? $mwb_rma_mail_basic_settings['mwb_rma_mail_from_email']:'';
				$fname =  isset($mwb_rma_mail_basic_settings['mwb_rma_mail_from_name'])? $mwb_rma_mail_basic_settings['mwb_rma_mail_from_name']:'';

				
				$approve = stripslashes(isset($mwb_rma_mail_refund_settings['mwb_rma_mail_return_approve_message'])? $mwb_rma_mail_refund_settings['mwb_rma_mail_return_approve_message']:'');


				$firstname = get_post_meta($orderid, '_billing_first_name', true);
				$lname = get_post_meta($orderid, '_billing_last_name', true);

				$fullname = $firstname." ".$lname;

				$approve = str_replace('[username]', $fullname, $approve);
				$approve = str_replace('[order]', "#".$orderid, $approve);
				$approve = str_replace('[siteurl]', home_url(), $approve);

				$mwb_rma_shortcode='';
				$mwb_rma_shortcode = $approve;
				$mwb_rma_shortcode = apply_filters( 'mwb_rma_add_shortcode_refund_approve_mail' , $mwb_rma_shortcode,$orderid);
				$approve = $mwb_rma_shortcode;
			
			$message_details='';
			$message_details='<div class="header">
						<h2>'.__('Your Refund Request is Approved', 'woo-refund-and-exchange-lite').'</h2>
					</div>
					<div class="content">
						<div class="reason">
							<p>'.$approve.'</p>
						</div>
						<div class="Order">
							<h4>Order #'.$orderid.'</h4>
							<table>
								<tbody>
									<tr>
										<th>'.__('Product', 'woo-refund-and-exchange-lite').'</th>
										<th>'.__('Quantity', 'woo-refund-and-exchange-lite').'</th>
										<th>'.__('Price', 'woo-refund-and-exchange-lite').'</th>
									</tr>';
									$order = wc_get_order($orderid);
									$final_stotal=0;
									$requested_products = $products[$date]['products'];

									if(isset($requested_products) && !empty($requested_products))
									{
										$total = 0;
										$mwb_get_refnd = get_post_meta( $orderid, 'mwb_rma_return_request_product',true );
										if( !empty( $mwb_get_refnd ) )
										{
											foreach ($mwb_get_refnd as $key => $value) 
											{
												if( isset( $value['amount'] ) )
												{
													$total_price = $value['amount'];
													break;
												}
											}
										}
										foreach( $order->get_items() as $item_id => $item )
										{
											foreach($requested_products as $requested_product)
											{
												if($item_id == $requested_product['item_id'])
												{

													if(isset($requested_product['variation_id']) && $requested_product['variation_id'] > 0)
													{
														$prod = wc_get_product($requested_product['variation_id']);

													}
													else
													{
														$prod = wc_get_product($requested_product['product_id']);
													}

													$prod_price = $requested_product['price'];
													$subtotal = $prod_price*$requested_product['qty'];
													$total += $subtotal;
													$item_meta      = new WC_Order_Item_Product( $item );
													$item_meta_html = wc_display_item_meta($item_meta,array('echo'=> false));
													$message_details.= '<tr>
													<td>'.$item['name'].'<br>';
														$message_details.= '<small>'.$item_meta_html.'</small>
														<td>'.$requested_product['qty'].'</td>
														<td>'.wc_price($requested_product['price']*$requested_product['qty']).'</td>
													</tr>';

												}
											}
										}			
										$message_details.= '<tr>
										<th colspan="2">Total:</th>
										<td>'.wc_price($total_price).'</td>
									</tr>';
								}
			$message_details.= ' <tr>
										<th colspan="2">'.__('Refund Total', 'woo-refund-and-exchange-lite').':</th>
										<td>'.wc_price($total_price).'</td>
									</tr>
								</tbody>
							</table>
						</div>';

			$message = create_mail_html($orderid,$message_details);
			$html_content = $message;

			$to = get_post_meta($orderid, '_billing_email', true);
			$headers = array();
			$headers[] = "From: $fname <$fmail>";
			$headers[] = "Content-Type: text/html; charset=UTF-8";
			$subject = isset($mwb_rma_mail_refund_settings['mwb_rma_mail_return_approve_subject'])? $mwb_rma_mail_refund_settings['mwb_rma_mail_return_approve_subject']:'';

			wc_mail($to, $subject, $html_content, $headers);

			$order->update_status('wc-refund-approved', __('User Request of Refund Product is approved','woo-refund-and-exchange-lite'));
			$order->calculate_totals();
			$response['response'] = 'success';
			echo json_encode($response);
			wp_die();
			
			}
		}
	}

	/**
	 * This function is to process cancel Refund request
	 * 
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	
	function mwb_rma_return_req_cancel_callback()
	{
		$check_ajax = check_ajax_referer( 'mwb-rma-ajax-security-string', 'security_check' );
		if ( $check_ajax ) {
			if(current_user_can('mwb-rma-refund-cancel'))
			{
				$orderid = sanitize_text_field($_POST['orderid']);
				$date = sanitize_text_field($_POST['date']);

				$products = get_post_meta($orderid, 'mwb_rma_return_request_product', true);

				//Fetch the return request product
				if(isset($products) && !empty($products))
				{
					foreach($products as $date=>$product)
					{
						if($product['status'] == 'pending')
						{
							$product_datas = $product['products'];
							$products[$date]['status'] = 'cancel';
							$approvdate = date("d-m-Y");
							$products[$date]['cancel_date'] = $approvdate;
							break;
						}
					}
				}

				//Update the status
				update_post_meta($orderid, 'mwb_rma_return_request_product', $products);

				$request_files = get_post_meta($orderid, 'mwb_rma_return_attachment', true);
				if(isset($request_files) && !empty($request_files))
				{
					foreach($request_files as $date=>$request_file)
					{
						if($request_file['status'] == 'pending')
						{
							$request_files[$date]['status'] = 'cancel';
						}
					}
				}

				//Update the status
				update_post_meta($orderid, 'mwb_rma_return_attachment', $request_files);

				$mwb_rma_mail_basic_settings = get_option('mwb_rma_mail_basic_settings',array());
				$mwb_rma_mail_refund_settings = get_option('mwb_rma_mail_refund_settings',array());

				$order = wc_get_order($orderid);

				$fmail =  isset($mwb_rma_mail_basic_settings['mwb_rma_mail_from_email'])? $mwb_rma_mail_basic_settings['mwb_rma_mail_from_email']:'';
				$fname =  isset($mwb_rma_mail_basic_settings['mwb_rma_mail_from_name'])? $mwb_rma_mail_basic_settings['mwb_rma_mail_from_name']:'';

				
				$message = stripslashes(isset($mwb_rma_mail_refund_settings['mwb_rma_mail_return_cancel_message'])? $mwb_rma_mail_refund_settings['mwb_rma_mail_return_cancel_message']:'');
				$firstname = get_post_meta($orderid, '_billing_first_name', true);
				$lname = get_post_meta($orderid, '_billing_last_name', true);

				$fullname = $firstname." ".$lname;
				$message = str_replace('[username]', $fullname, $message);
				$message = str_replace('[order]', "#".$orderid, $message);
				$message = str_replace('[siteurl]', home_url(), $message);

				$mwb_rma_shortcode='';
				$mwb_rma_shortcode = $message;
				$mwb_rma_shortcode = apply_filters( 'mwb_rma_add_shortcode_refund_cancel_mail' , $mwb_rma_shortcode,$orderid);
				$message = $mwb_rma_shortcode;

				$mail_header = stripslashes(isset($mwb_rma_mail_basic_settings['mwb_rma_mail_header'])? $mwb_rma_mail_basic_settings['mwb_rma_mail_header']:'');
				$mail_footer = stripslashes(isset($mwb_rma_mail_basic_settings['mwb_rma_mail_footer'])? $mwb_rma_mail_basic_settings['mwb_rma_mail_footer']:'');
				$subject = isset($mwb_rma_mail_refund_settings['mwb_rma_mail_return_cancel_subject'])? $mwb_rma_mail_refund_settings['mwb_rma_mail_return_cancel_subject']:'';

				$html_content = '<html>
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
				</head>
				<body>
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td style="text-align: center; margin-top: 30px; padding: 10px; color: #99B1D8; font-size: 12px;">
								'.$mail_header.'
							</td>
						</tr>
						<tr>
							<td>
								<table align="center" cellpadding="0" cellspacing="0" style="border-collapse: collapse; font-family:Open Sans; max-width: 600px; width: 100%;">
									<tr>
										<td style="padding: 36px 48px; width: 100%; background-color:#557DA1;color: #fff; font-size: 30px; font-weight: 300; font-family:helvetica;">'.$subject.'</td>
									</tr>
									<tr>
										<td style="width:100%; padding: 36px 48px 10px; background-color:#fdfdfd; font-size: 14px; color: #737373;">'.$message.'</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td style="text-align: center; margin-top: 30px; color: #99B1D8; font-size: 12px;">
								'.$mail_footer.'
							</td>
						</tr>
					</table>
				</body>
				</html>';

				$to = get_post_meta($orderid, '_billing_email', true);
				$headers[] = "From: $fname <$fmail>";
				$headers[] = "Content-Type: text/html; charset=UTF-8";
				
				wc_mail($to, $subject, $html_content, $headers);
			
				$order->update_status('wc-refund-cancelled', __('User Request of Refund Product is approevd','woo-refund-and-exchange-lite'));
				$response['response'] = 'success';
				echo json_encode($response);
				die;
			}
		}
	}

	/**
	 * Update left amount because amount is refunded.
	 * 
	 * @param $order_get_id , $refund_get_id
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */

	public function mwb_rma_action_woocommerce_order_refunded( $order_get_id, $refund_get_id )
	{
		update_post_meta($refund_get_id['order_id'],'mwb_rma_refund_amount','yes');
	}

	/**
	 * Manage stock when product is actually back in stock.
	 * 
	 * @name mwb_rma_manage_stock
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */

	public function mwb_rma_manage_stock()
	{ 
		$check_ajax = check_ajax_referer( 'mwb-rma-ajax-security-string', 'security_check' );
		if ( $check_ajax ) {
			if(current_user_can('mwb-rma-refund-manage-stock'))
			{
				$order_id = isset($_POST['order_id']) ? $_POST['order_id'] : 0 ;
				$order_id = sanitize_text_field($order_id);
				if($order_id > 0)
				{
					$mwb_rma_type = isset($_POST['type']) ? $_POST['type'] : '' ;
					$ms_flag=true;
					if($mwb_rma_type != '')
					{
						if($mwb_rma_type == 'mwb_rma_return')
						{ 
							$mwb_rma_refund_settings = get_option( 'mwb_rma_refund_settings' ,array());
							$manage_stock = isset($mwb_rma_refund_settings['mwb_rma_return_request_manage_stock'])? $mwb_rma_refund_settings['mwb_rma_return_request_manage_stock']:'';
							if($manage_stock == "on")
							{
								$mwb_rma_return_data = get_post_meta($order_id, 'mwb_rma_return_request_product', true);
								if(is_array($mwb_rma_return_data) && !empty($mwb_rma_return_data))
								{
									foreach ($mwb_rma_return_data as $date => $requested_data) {
										$mwb_rma_returned_products = $requested_data['products'];

										if(is_array($mwb_rma_returned_products) && !empty($mwb_rma_returned_products))
										{
											foreach ($mwb_rma_returned_products as $key => $product_data) 
											{
												if($product_data['variation_id'] > 0)
												{
													$product =wc_get_product($product_data['variation_id']);
													$prod_id = $product_data['variation_id'];
												}
												else
												{
													$product = wc_get_product($product_data['product_id']);
													$prod_id = $product_data['product_id'];
												}
												$pro_avail = $product->managing_stock();
												if(!$pro_avail){
													$ms_flag=false;
												}

												if($product->managing_stock())
												{
													$get_update_stock=get_post_meta( $order_id,$prod_id.'mwb_rma_manage_stock_for_return',true);

													if(isset($get_update_stock) && $get_update_stock != 'yes'){
														$avaliable_qty = $product_data['qty'];
														if($product_data['variation_id'] > 0)
														{
															$total_stock = get_post_meta($product_data['variation_id'],'_stock',true);
															$total_stock = $total_stock + $avaliable_qty;
															wc_update_product_stock( $product_data['variation_id'],$total_stock, 'set' );
														}
														else
														{
															$total_stock = get_post_meta($product_data['product_id'],'_stock',true);
															$total_stock = $total_stock + $avaliable_qty;
															wc_update_product_stock( $product_data['product_id'],$total_stock, 'set' );
														}

														update_post_meta($order_id,$prod_id.'mwb_rma_manage_stock_for_return','yes');
													
													}
												}
											}
										}
									}
								}
							}
						}	
					}

				}
				if($ms_flag){
					$response['result'] = 'success';
					$response['msg'] = __('Product Stock is updated Succesfully.','woo-refund-and-exchange-lite');
				}else{
					$response['result'] = false;
					$response['msg'] = __('Product Stock is not updated as manage stock setting of product is disable.','woo-refund-and-exchange-lite');
				}
				echo json_encode($response);
				wp_die();
			}
		}
	}

}