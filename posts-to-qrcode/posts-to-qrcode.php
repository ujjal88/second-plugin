<?php
/*
Plugin Name: Posts To QR Code
Plugin URI: https://uzzaldev.com
Description: Display QR Code under ever posts
Version: 1.0
Author: uzzaldev
Author URI: https://uzzaldev.com
License: GPLv2 or later
Text Domain: posts-to-qrcode
Domain Path: /languages/
*/

/*function wordcount_activation_hook(){}
register_activation_hook(__FILE__,"wordcount_activation_hook");

function wordcount_deactivation_hook(){}
register_deactivation_hook(__FILE__,"wordcount_deactivation_hook");*/

function wordcount_load_textdomain() {
    load_plugin_textdomain( 'posts-to-qrcode', false, dirname( __FILE__ ) . "/languages" );
}

function pqrc_display_qr_code($content){
	$current_post_id = get_the_ID();
	$current_post_title = get_the_title($current_post_id);
	$current_post_url =urlencode(get_the_permalink($current_post_id));
	$current_post_type = get_post_type($current_post_id);

	/**	
	*	post type check
	*/

	$excluded_post_type = apply_filters('pqrc_excluded_post_types', array());
	if(in_array($current_post_type,$excluded_post_type )){
		return $content;
	}

	/**	
	*	dimenions hook
	*/

	$dimenions= apply_filters('pqrc_pqrcode_dimenions','150x150');

	/**	
	*	image attributes
	*/

	$image_attributes = apply_filters('pqrc_image_attributes',null);

	$image_src = sprintf('https://api.qrserver.com/v1/create-qr-code/?size=%s&data=%s',$dimenions,$current_post_url);
	$content.= sprintf("<div class='qrcode'><img %s src='%s' alt='%s' /></div>",$image_airtut,$image_src,$current_post_title);

	return $content;
}

add_filter('the_content','pqrc_display_qr_code');