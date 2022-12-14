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
	$height = get_option('pqrc_height');
	$width = get_option('pqrc_width');
	$height = $height ? $height: 180;
	$width = $width ? $width: 180;

	$dimenions= apply_filters('pqrc_qrcode_dimension',"{$height}x{$width}");

	// Dimension Hook
    $height    = get_option( 'pqrc_height' );
    $width     = get_option( 'pqrc_width' );
    $height    = $height ? $height : 180;
    $width     = $width ? $width : 180;
    $dimension = apply_filters( 'pqrc_qrcode_dimension', "{$width}x{$height}" );

	/**	
	*	image attributes
	*/

	$image_attributes = apply_filters('pqrc_image_attributes',null);

	$image_src = sprintf('https://api.qrserver.com/v1/create-qr-code/?size=%s&data=%s',$dimenions,$current_post_url);
	$content.= sprintf("<div class='qrcode'><img %s src='%s' alt='%s' /></div>",$image_airtut,$image_src,$current_post_title);

	return $content;
}

add_filter('the_content','pqrc_display_qr_code');

function pqrc_setting_init(){

	add_settings_section('pqrc_section',__('Posts to QR Code','posts-to-qrcode'),'pqrc_section_callback','general');

	add_settings_field('pqrc_height',__('QR Code Hight','posts-to-qrcode'),'pqrc_display_field','general','pqrc_section',array('pqrc_height'));
	add_settings_field('pqrc_width',__('QR Code width','posts-to-qrcode'),'pqrc_display_field','general','pqrc_section',array('pqrc_width'));
	// add_settings_field('pqrc_exit',__('QR Code exit','posts-to-qrcode'),'pqrc_display_field','general','pqrc_section',array('pqrc_exit'));
	add_settings_field('pqrc_select',__('Pqrc DropDown','posts-to-qrcode'),'pqrc_display_select','general','pqrc_section');

	register_setting('general','pqrc_height', array('sanitize_callback' => 'esc_attr'));
	register_setting('general','pqrc_width', array('sanitize_callback' => 'esc_attr'));
	// register_setting('general','pqrc_exit', array('sanitize_callback' => 'esc_attr'));
	register_setting('general','pqrc_select', array('sanitize_callback' => 'esc_attr'));
}

// function pqrc_display_select(){
// 	$option = get_option('pqrc_select');
// 	$country = array(
// 		'None',
// 		'India',
// 		'Bangladesh',
// 		'Vutan',
// 		'Neple',
// 		'Maldip'

// 	);
// 	printf( "<select id='%s' name='%s' ">, 'pqrc_select', 'pqrc_select');
// 	$selected = '';
// 	if ($option == $country )$selected='selected';
// 	foreach ($country as $country) {
// 		printf("<option value='%s',%s >%s</option>",'$country','$selected','$country');
// 	}
// 	echo "</select>";
// }

function pqrc_display_select() {
    $option = get_option( 'pqrc_select' );
    $country = array(
		'None',
		'India',
		'Bangladesh',
		'Vutan',
		'Neple',
		'Maldip'

	);

    printf( '<select id="%s" name="%s">', 'pqrc_select', 'pqrc_select' );
    foreach ( $country as $country ) {
        $selected = '';
        if ( $option == $country ) {
            $selected = 'selected';
        }
        printf( '<option value="%s" %s>%s</option>', $country, $selected, $country );
    }
    echo "</select>";
}


function pqrc_section_callback(){
	echo "<p>".__('Setting for qr plugin','posts-to-qrcode')."</p>";
}

function pqrc_display_field($args){
	$option = get_option($args[0]);
	printf( "<input type='text' id='%s' name='%s' value='%s'/>", $args[0], $args[0], $option );
}

// function pqrc_hight_display() {
//     $height = get_option( 'pqrc_height' );
//     printf( "<input type='text' id='%s' name='%s' value='%s'/>", 'pqrc_height', 'pqrc_height', $height );
// }

// function pqrc_width_display() {
//     $width = get_option( 'pqrc_width' );
//     printf( "<input type='text' id='%s' name='%s' value='%s'/>", 'pqrc_width', 'pqrc_width', $width );
// }

add_action("admin_init",'pqrc_setting_init');
