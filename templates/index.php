<?php 
	$slug = $_GET['page'];
	$tabs = array( 'wpce-news' => __('What\'s New', 'wpce'), 'wpce-doc' => __('Documentation', 'wpce'), 'wpce-credits' => __('Credits', 'wpce') );
	$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'wpce-news';
?>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '722061811228106',
      xfbml      : true,
      version    : 'v2.4'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
<div class="wrap about-wrap">
	<h1><?php echo sprintf( __( 'WP Cookies Enabler %s', 'wpce' ), preg_replace( '/^(\d+)(\.\d+)?(\.\d)?/', '$1$2', WPCE_VERSION ) ) ?></h1>

	<div class="about-text"><?php _e( 'WP Cookies Enabler is an easy and lightweight solution to preventively block third-party cookies installed by js and to comply with the EU cookie law.', 'wpce' ) ?></div>
	<div class="wp-badge wpce-badge"><?php echo sprintf( __( 'Version %s', 'wpce' ), WPCE_VERSION ) ?></div>
	
	<p class="wpce-actions">
		<a href="<?php echo esc_attr( admin_url( 'admin.php?page=wpce-main-options' ) ) ?>" class="button button-primary"><?php _e( 'Go to Settings', 'wpce' ) ?></a>		
		<div class="fb-like" data-href="https://www.facebook.com/stilekiwi" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>
	</p>
	
	<h2 class="nav-tab-wrapper">
	<?php foreach ( $tabs as $tab_slug => $title ): ?>
		<?php $url = 'admin.php?page=' . rawurlencode( $slug ) . '&tab=' . rawurlencode( $tab_slug ); ?>
		<a href="<?php echo esc_attr( is_network_admin() ? network_admin_url( $url ) : admin_url( $url ) ) ?>"
		   class="nav-tab<?php echo $active_tab === $tab_slug ? esc_attr( ' nav-tab-active' ) : '' ?>">
			<?php echo $title ?>
		</a>
	<?php endforeach; ?>
</h2>
	<?php include_once( plugin_dir_path( __FILE__ ) . $active_tab . '.php'); ?>
	
	<?php include_once( plugin_dir_path( __FILE__ ) . 'footer.php'); ?>
</div>