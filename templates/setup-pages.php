<?php

if ( is_admin() ){
	add_action( 'admin_menu', 'wpce_info_pages' );
	add_action( 'admin_enqueue_scripts', 'wp_cookies_enabler_admin_scripts' );
}

function wp_cookies_enabler_admin_scripts() {
	wp_register_style( 'wpce-admin-css', plugins_url('/css/wpce_admin_styles.css', __FILE__), false, '1.0.0', 'all');
	wp_enqueue_style( 'wpce-admin-css');
}

function wpce_info_pages() {
	if ( current_user_can( 'manage_options' ) )  {
		add_submenu_page( 'wpce-main-options', __('WP Cookies Enabler Info', 'wpce'), __('Info', 'wpce'), 'manage_options', 'wpce-info', 'wpce_info_render');
	}
}

function wpce_info_render() {
	include_once( plugin_dir_path( __FILE__ ) . 'index.php');
}
