<div class="wpce-news-tab changelog">
	<div class="headline-feature">
		<div class="feature-section">
			<img class="featured-image" src="<?php echo plugins_url('/assets/cute-cookie.png', __FILE__); ?>" title="<?php _e('COOKIE LOVES YOU!', 'wpce') ?>" />

			<h3><?php _e( 'EU legislation on cookies', 'wpce' ); ?></h3>

			<p><?php printf(__( 'EUROPEAN websites must follow the Commission\'s guidelines on <a href="%s" target="blank">privacy and data protection</a> and inform users that cookies are not being used to gather information unnecessarily.', 'wpce' ), 'http://ec.europa.eu/ipg/basics/legal/data_protection/index_en.htm'); ?></p>
			<p><?php printf(__( 'The <a href="%s" target="blank">ePrivacy directive</a> – more specifically Article 5(3) – requires prior informed consent for storage or for access to information stored on a user\'s terminal equipment. In other words, you must ask users if they agree to most cookies and similar technologies before the site starts to use them.', 'wpce' ), 'http://eur-lex.europa.eu/LexUriServ/LexUriServ.do?uri=CELEX:32002L0058:EN:HTML'); ?></p>
			<p>&nbsp;</p>
			<p><?php _e( 'For <strong>consent</strong> to be valid, it must be <strong>informed</strong>, <strong>specific</strong>, <strong>freely given</strong> and must constitute a real indication of the individual\'s wishes.', 'wpce' ); ?></p>
		</div>
	</div>

	<div class="feature-section three-col">
		<div class="col featured-image">
			<img src="<?php echo plugins_url('/assets/wpce-script.png', __FILE__); ?>"/>
			<h4><?php _e( 'Block cookies from script tags...', 'wpce' ); ?></h4>

			<p><?php _e( 'Prevent javascript from installing cookies through <code>&lt;script&gt;</code> tags, ie Google Analytics.', 'wpce' ); ?></p>
		</div>
		<div class="col featured-image">
			<img src="<?php echo plugins_url('/assets/wpce-iframe.png', __FILE__); ?>"/>
			<h4><?php _e( '... iframes...', 'wpce' ); ?></h4>

			<p><?php _e( 'Prevent iframes that could install cookies from being loaded, ie Google Maps.', 'wpce' ); ?></p>
		</div>
		<div class="col featured-image">
			<img src="<?php echo plugins_url('/assets/wpce-document-write.png', __FILE__); ?>"/>
			<h4><?php _e( '... and also problematic scripts', 'wpce' ); ?></h4>

			<p><?php _e( 'Some scripts use <code>document.write</code> and can\'t be executed asynchronusly. Thanks to Postscribe it is possible to defer the loading of these script without blocking the page.', 'wpce' ); ?></p>
		</div>
	</div>

	<div class="feature-section">
		<h3><?php _e( 'Changelog', 'wpce' ); ?></h3>
		
		<h4>1.0</h4>
		<ul class="wpce-list">
			<li><?php _e( 'First release', 'wpce' ); ?></li>
		</ul>
	</div>
</div>