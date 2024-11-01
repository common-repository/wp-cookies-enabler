<div class="wpce-doc-tab changelog">
	<p class="about-description"><?php _e( 'WP Cookies Enabler will not block cookies out of the box. Even though the banner is displayed, you still have to put your hands on the code to actually stop cookies. Here it is what you have to do.', 'wpce' ); ?></p>
	
	<div class="feature-section">
		<h3><?php _e('Blocking cookies from script tags', 'wpce'); ?></h3>
		<p><?php _e('Add the class <code>"ce-script"</code> and <code>type="text/plain"</code> to every script tag that installs cookies', 'wpce'); ?></p>
		<pre class="wpce-code">&lt;script type="text/plain" class="ce-script"&gt;
	// GA Demo
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-XXXXX-X']);
	_gaq.push(['_trackPageview']);
&lt;/script&gt;

&lt;script type="text/plain" class="ce-script"&gt;
	// FB Share Demo
	(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
&lt;/script&gt;</pre>
	</div>
	
	<div class="feature-section">		
		<h3><?php _e('Blocking cookies from iframes', 'wpce'); ?></h3>
		<p><?php _e('For <code>iframes</code>, change the <code>src</code> attribute to <code>data-ce-src</code> and add the class <code>"ce-iframe"</code>', 'wpce'); ?></p>
		<pre class="wpce-code">&lt;iframe class="ce-iframe" data-ce-src="https://player.vimeo.com/video/1084537" width="500" height="281"&gt;</pre>
		<h4><?php _e('iframe placeholder', 'wpce'); ?></h4>
		<p><?php printf(__('You can enable placeholders for blocked iframes using the related option in the <a href="%s">General Settings</a> page.', 'wpce'), esc_attr( admin_url( 'admin.php?page=wpce-main-options' ) )); ?></p>
		<p><?php _e('Doing so, a custom placeholder for blocked iframes will be inserted after the hidden <code>iframe</code> element.', 'wpce'); ?></p>
		<p><?php _e('You can customize the placeholder HTML using his relative text editor in the Settings page.', 'wpce'); ?></p>
	</div>
	
	<div class="feature-section">		
		<h3><?php _e('Problematic scripts', 'wpce'); ?></h3>
		<p><?php _e('Some scripts use <code>document.write</code> and can\'t be executed asynchronusly. This plugin load Postscribe to defer the loading of these script without blocking the page.', 'wpce'); ?></p>
		<p><?php _e('Just change the <code>src</code> to <code>data-ce-src</code> and add the class <code>ce-script</code>.', 'wpce'); ?></p>
		<pre class="wpce-code">&lt;script data-ce-src="http://pagead2.googlesyndication.com/pagead/show_ads.js" class="ce-script"&gt;&lt;/script&gt;</pre>
	</div>
	
	<div class="feature-section">		
		<h3><?php _e('DOM hooks', 'wpce'); ?></h3>
		<p><?php _e('Adding the <code>ce-accept</code> class to any element will make it act as an accept button, enabling the cookies on click.', 'wpce'); ?></p>
		<p><?php _e('Adding the <code>ce-dismiss</code> class to any element will make it act as a dismiss button, removing the notice banner on click.', 'wpce'); ?></p>
		<p><?php _e('Adding the <code>ce-disable</code> class to any element will make it act as a disable button, removing the notice banner and disabling cookies on click.', 'wpce'); ?></p>
	</div>
</div>
