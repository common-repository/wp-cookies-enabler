<?php
/*
Plugin Name: WP Cookies Enabler
Plugin URI: http://www.stilekiwi.it/wp-cookies-enabler/
Description: Easy and lightweight solution to preventively block third-party cookies installed by js and to comply with the EU cookie law.
Author: Filippo Bodei
Author URI: http://www.stilekiwi.it/
Author e-mail: filippobodei@hotmail.com
Text Domain: wp-cookies-enabler
Domain Path: /languages/
Version: 1.0.1
License: GPLv2
*/
/*	Copyright 2015 Filippo Bodei  (email : filippobodei@hotmail.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Silence is golden; exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Current  version
 */
if ( ! defined( 'WPCE_VERSION' ) ) {
	/**
	 *
	 */
	define( 'WPCE_VERSION', '1.0' );
}
/**
 * Load textdomain
 */
function wpce_lang_init() {
	load_plugin_textdomain( 'wpce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action('plugins_loaded', 'wpce_lang_init');
/**
 * Bootstrap CMB2
 */
if ( file_exists( dirname( __FILE__ ) . '/inc/cmb2/init.php' ) && is_admin() ) {
	require_once dirname( __FILE__ ) . '/inc/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/inc/CMB2/init.php' ) && is_admin() ) {
	require_once dirname( __FILE__ ) . '/inc/CMB2/init.php';
}

/**
 * Load the CMB2 powered admin options page
 */
if ( file_exists( dirname( __FILE__ ) . '/admin-options.php' ) && is_admin() ) {
	require_once dirname( __FILE__ ) . '/admin-options.php';
}

/**
 * Load the info pages
 */
if ( file_exists( dirname( __FILE__ ) . '/templates/setup-pages.php' ) && is_admin() ) {
	require_once dirname( __FILE__ ) . '/templates/setup-pages.php';
}

/**
 * Enqueue scripts and styles.
 *
 */
function wp_cookies_enabler_scripts() {
	
	if ( ! is_admin() ) {
		// always load engine js
		wp_register_script('wp-cookies-enabler', plugins_url('/inc/cookies-enabler/cookies-enabler.js', __FILE__), false, '1.0.0', true);
		wp_enqueue_script( 'wp-cookies-enabler' );
		wp_register_script('wpce-postscribe', plugins_url('/inc/postscribe/postscribe.js', __FILE__), false, '1.0.0', true);
		wp_enqueue_script( 'wpce-postscribe' );
		
		// get plugin options
		$wpce_main_options = get_option( 'wpce-main-options' );
		$wpce_aspect_options = get_option( 'wpce-aspect-options' );
		
		// get cookie name
		$cookie = $wpce_main_options['_wpce_cookie_name'];
		// if cookie does not exist load styles too
		if ( ! is_admin() && !isset($_COOKIE[$cookie]) ) {
			wp_register_style( 'wpce-css', plugins_url('/inc/cookies-enabler/cookies-enabler.css', __FILE__), false, '1.0.0', 'all');
			wp_enqueue_style( 'wpce-css');
			
			$banner_class = $wpce_main_options[ '_wpce_banner_class' ];
			$placeholder_class = $wpce_main_options[ '_wpce_placeholder_class' ];
			$accept_class = $wpce_main_options[ '_wpce_accept_class' ];
			$dismiss_class = $wpce_main_options[ '_wpce_dismiss_class' ];
			$disable_class = $wpce_main_options[ '_wpce_disable_class' ];
			
			$text_color = $wpce_aspect_options[ '_wpce_text_color' ];
			$banner_background = $wpce_aspect_options[ '_wpce_banner_background' ];
			$banner_position = $wpce_aspect_options[ '_wpce_banner_position' ];
			$custom_css = $wpce_aspect_options[ '_wpce_custom_css' ];
			
			if ( empty( $banner_class ) ) $banner_class = 'ce-banner';
			if ( empty( $placeholder_class ) ) $placeholder_class = 'ce-iframe-placeholder';
			
			if ( empty( $accept_class ) ) $accept_class = 'ce-accept';
			if ( empty( $dismiss_class ) ) $dismiss_class = 'ce-dismiss';
			if ( empty( $disable_class ) ) $disable_class = 'ce-disable';
			
			if ( empty( $text_color ) ) $text_color = '#fff';
			if ( empty( $banner_background ) ) $banner_background = '#222';
			if ( empty( $banner_position ) ) $banner_position = 'top';
			if ( empty( $custom_css ) ) $custom_css = '';
			$top = 0;
			$bottom = 'auto';
			if ($banner_position == 'bottom') {
				$top = 'auto';
				$bottom = 0;
			}
			else if ($banner_position == 'modal') {
				
			}
			ob_start();
			?>
			.<?php echo $banner_class; ?> {
				background-color: <?php echo $banner_background; ?>;
				color: <?php echo $text_color; ?>;
				position: fixed;
				top: <?php echo $top; ?>;
				bottom: <?php echo $bottom; ?>;
				left: 0;
				right: 0;
				padding: 1em;
				z-index: 1000;
			}

			.<?php echo $placeholder_class; ?> {
				padding: 1em;
				margin: 1em 0;
			}
			
			<?php echo $custom_css; ?>
			<?php
			wp_add_inline_style( 'wpce-css', ob_get_clean() );
			
			// init cookies-enabler.js
			add_action( 'wp_footer', 'wpce_init' );
		}
		else {
			// cookies-enabler.js simplified init
			add_action( 'wp_footer', 'wpce_simple_init' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'wp_cookies_enabler_scripts' );

/**
 * Enqueue ACE Editor scripts
 *
 */
function load_ace_scripts() {
	if($_GET['page'] == 'wpce-aspect-options') {
		wp_register_script('wpce-ace', plugins_url('/inc/ace/ace.js', __FILE__), false, '1.0.0', true);
		wp_enqueue_script( 'wpce-ace' );
		wp_register_script('wpce-mode-css', plugins_url('/inc/ace/mode-css.js', __FILE__), false, '1.0.0', true);
		wp_enqueue_script( 'wpce-mode-css' );
		wp_register_script('wpce-worker-css', plugins_url('/inc/ace/worker-css.js', __FILE__), false, '1.0.0', true);
		wp_enqueue_script( 'wpce-worker-css' );
		wp_register_script('wpce-theme-vibrant_ink', plugins_url('/inc/ace/theme-vibrant_ink.js', __FILE__), false, '1.0.0', true);
		wp_enqueue_script( 'wpce-theme-vibrant_ink' );
		wp_register_script('wpce-ace-init', plugins_url('/inc/ace/ace-init.js', __FILE__), false, '1.0.0', true);
		wp_enqueue_script( 'wpce-ace-init' );
	}
}
add_action( 'admin_enqueue_scripts', 'load_ace_scripts' );

/**
 * Redirect after activation
 *
 */
register_activation_hook(__FILE__, 'wpce_plugin_activate');
add_action('admin_init', 'wpce_plugin_redirect');

function wpce_plugin_activate() {
    add_option('wpce_plugin_do_activation_redirect', true);
}

function wpce_plugin_redirect() {
    if (get_option('wpce_plugin_do_activation_redirect', false)) {
        delete_option('wpce_plugin_do_activation_redirect');
        if(!isset($_GET['activate-multi']))
        {
            wp_redirect('admin.php?page=wpce-info');
        }
    }
}

/**
 * Add Cookie Enabler init in footer
 *
 */
function wpce_init() {
	// get plugin options
	$wpce_main_options = get_option( 'wpce-main-options' );
	
	$scriptClass = $wpce_main_options[ '_wpce_script_class' ];
	$iframeClass = $wpce_main_options[ '_wpce_iframe_class' ];
	$acceptClass = $wpce_main_options[ '_wpce_accept_class' ];
	$dismissClass = $wpce_main_options[ '_wpce_dismiss_class' ];
	$bannerClass = $wpce_main_options[ '_wpce_banner_class' ];
	$bannerHTML = str_replace(array("\r", "\n"), '', wpautop(_(addslashes($wpce_main_options[ '_wpce_banner_html' ]))));
	$eventScroll = $wpce_main_options[ '_wpce_event_scroll' ];
	$scrollOffset = $wpce_main_options[ '_wpce_scroll_offset' ];
	$clickOutside = $wpce_main_options[ '_wpce_click_outside' ];
	$cookieName = $wpce_main_options[ '_wpce_cookie_name' ];
	$cookieDuration = $wpce_main_options[ '_wpce_cookie_duration' ];
	$preventIframes = $wpce_main_options[ '_wpce_prevent_iframe' ];
	$iframesPlaceholder = $wpce_main_options[ '_wpce_iframe_placeholder' ];
	$iframesPlaceholderHTML = str_replace(array("\r", "\n"), '', wpautop(_(addslashes($wpce_main_options[ '_wpce_placeholder_html' ]))));
	$iframesPlaceholderClass = $wpce_main_options[ '_wpce_placeholder_class' ];
	
    echo '<script type="text/javascript">';
	echo 'window.onload = function() {';
	//echo 'window.addEventListener("scroll", function(){console.log(window.pageYOffset);});';
	echo 'COOKIES_ENABLER.init({';
	if ( !empty( $scriptClass ) ) echo 'scriptClass: \'' . $scriptClass . '\',';
	if ( !empty( $iframeClass ) ) echo 'iframeClass: \'' . $iframeClass . '\',';
	if ( !empty( $acceptClass ) ) echo 'acceptClass: \'' . $acceptClass . '\',';
	if ( !empty( $dismissClass ) ) echo 'dismissClass: \'' . $dismissClass . '\',';
	if ( !empty( $bannerClass ) ) echo 'bannerClass: \'' . $bannerClass . '\',';
	if ( !empty( $bannerHTML ) ) echo 'bannerHTML: \'' . $bannerHTML . '\',';
	if ( !empty( $eventScroll ) ) echo 'eventScroll: true,';
	else echo 'eventScroll: false,';
	if ( !empty( $scrollOffset ) ) echo 'scrollOffset: ' . $scrollOffset . ',';
	if ( !empty( $clickOutside ) ) echo 'clickOutside: true,';
	else echo 'clickOutside: false,';
	if ( !empty( $cookieName ) ) echo 'cookieName: \'' . $cookieName . '\',';
	if ( !empty( $cookieDuration ) ) echo 'cookieDuration: \'' . $cookieDuration . '\',';
	if ( !empty( $preventIframes ) ) echo 'preventIframes: \'' . $preventIframes . '\',';
	if ( !empty( $iframesPlaceholder ) ) echo 'iframesPlaceholder: true,';
	else echo 'iframesPlaceholder: false,';
	if ( !empty( $iframesPlaceholderHTML ) ) echo 'iframesPlaceholderHTML: \'' . $iframesPlaceholderHTML . '\',';
	if ( !empty( $iframesPlaceholderClass ) ) echo 'iframesPlaceholderClass: \'' . $iframesPlaceholderClass . '\',';
	echo '});';
	echo '}';
    echo '</script>';
}

/**
 * Add Cookie Enabler simple init in footer
 *
 */
function wpce_simple_init() {
    echo '<script type="text/javascript">window.onload = function() { COOKIES_ENABLER.init(); }</script>';
}