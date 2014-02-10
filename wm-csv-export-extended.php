<?php
/*
Plugin Name: WooCommerce Customer/Order CSV Export Extended (WM)
Description: Extends the CSV Order exports to include the custom meta fields completed during checkout.
*/
class CSVExportsExtended {
	function __construct() {
		if(		is_plugin_active( 'woocommerce/woocommerce.php' ) 
			&& 	is_plugin_active( 'woocommerce-customer-order-csv-export/woocommerce-customer-order-csv-export.php' ) 
			&& 	is_plugin_active( 'woocommerce-checkout-manager/woocommerce-checkout-manager.php' ) ):
				add_filter('wc_customer_order_csv_export_order_headers',	array($this,'inject_custom_headers'), 	10 , 1 );
				add_filter('wc_customer_order_csv_export_order_row',		array($this,'inject_custom_metas'), 	10 , 2 );
		endif;
	}
	function inject_custom_headers($column_headers) {
		$customHeaders = get_option('wccs_settings');
		foreach($customHeaders['buttons'] as $custom) {
			if ( ! empty( $custom['label'] ) ) 
				$column_headers[$custom['cow']] = $custom['label'];
		}
		return $column_headers;
	}
	function inject_custom_metas($order_data, $order) {
		$customHeaders = get_option('wccs_settings');
		foreach($customHeaders['buttons'] as $custom) {
			if ( ! empty( $custom['label'] ) ) 
				$order_data[0][$custom['cow']] = get_post_meta( $order->id , $custom['label'], true ) ;
		}
		return $order_data;
	}
}
$CSVExportsExtended = new CSVExportsExtended();
?>