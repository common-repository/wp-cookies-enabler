<?php
/**
 * CMB2 Admin Options
 *
 * @author    Filippo Bodei <info@stilekiwi.it>
 * @link      http://www.stilekiwi.it
 * @version   0.1.0
 */

class Wpce_Admin {
    /**
     * Default Option key
     * @var string
     */
    private $key = 'wpce_options';

    /**
     * Array of metaboxes/fields
     * @var array
     */
    protected $option_metabox = array();

    /**
     * Options Page title
     * @var string
     */
    protected $title = '';

    /**
     * Options Tab Pages
     * @var array
     */
    protected $options_pages = array();

    /**
     * Constructor
     * @since 0.1.0
     */
    public function __construct() {
        // Set our title
        $this->title = __( 'WP Cookies Enabler', 'wpce' );
    }

    /**
     * Initiate our hooks
     * @since 0.1.0
     */
    public function hooks() {
        add_action( 'admin_init', array( $this, 'init' ) );
        add_action( 'admin_menu', array( $this, 'add_options_page' ) ); //create tab pages
    }

    /**
     * Register our setting tabs to WP
     * @since  0.1.0
     */
    public function init() {
    	$option_tabs = self::option_fields();
        foreach ($option_tabs as $index => $option_tab) {
        	register_setting( $option_tab['id'], $option_tab['id'] );
        }
    }

    /**
     * Add menu options page
     * @since 0.1.0
     */
    public function add_options_page() {
		if ( current_user_can( 'manage_options' ) )  {
			$option_tabs = self::option_fields();
			foreach ($option_tabs as $index => $option_tab) {
				if ( $index == 0) {
					$this->options_pages[] = add_menu_page( $this->title, $this->title, 'manage_options', $option_tab['id'], array( $this, 'admin_page_display' ), plugin_dir_url( __FILE__ ) . 'templates/assets/16px.png' ); //Link admin menu to first tab
					add_submenu_page( $option_tabs[0]['id'], $this->title, $option_tab['title'], 'manage_options', $option_tab['id'], array( $this, 'admin_page_display' ) ); //Duplicate menu link for first submenu page
				} else {
					$this->options_pages[] = add_submenu_page( $option_tabs[0]['id'], $this->title, $option_tab['title'], 'manage_options', $option_tab['id'], array( $this, 'admin_page_display' ) );
				}
				// Include CMB2 CSS in the head to avoid FOUT
				add_action( "admin_print_styles-{$this->options_pages[$index]}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
			}
		}
    }

    /**
     * Admin page markup. Mostly handled by CMB2
     * @since  0.1.0
     */
    public function admin_page_display() {
    	$option_tabs = self::option_fields(); //get all option tabs
    	$tab_forms = array();     	   	
        ?>
        <div class="wrap about-wrap cmb2_options_page <?php echo $this->key; ?>">        	
            <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
            
            <!-- Options Page Nav Tabs -->           
            <h2 class="nav-tab-wrapper">
            	<?php foreach ($option_tabs as $option_tab) :
            		$tab_slug = $option_tab['id'];
            		$nav_class = 'nav-tab';
            		if ( $tab_slug == $_GET['page'] ) {
            			$nav_class .= ' nav-tab-active'; //add active class to current tab
            			$tab_forms[] = $option_tab; //add current tab to forms to be rendered
            		}
            	?>            	
            	<a class="<?php echo $nav_class; ?>" href="<?php menu_page_url( $tab_slug ); ?>"><?php esc_attr_e($option_tab['title']); ?></a>
            	<?php endforeach; ?>
            </h2>
            <!-- End of Nav Tabs -->

            <?php foreach ($tab_forms as $tab_form) : //render all tab forms (normaly just 1 form) ?>
            <div id="<?php esc_attr_e($tab_form['id']); ?>" class="group">
            	<?php cmb2_metabox_form( $tab_form, $tab_form['id'], array(
					'save_button' => __( 'Save Settings', 'wpce' ),
					'reset_button' => __( 'Reset to Default', 'wpce' ),
					) ); ?>
            </div>
            <?php endforeach; ?>
			<?php include_once( plugin_dir_path( __FILE__ ) . 'templates/footer.php'); ?>
		</div>
        <?php
    }

	/**
     * Defines the theme option metabox and field configuration
     * @since  0.1.0
     * @return array
     */
    public function option_fields() {
		
		$prefix = '_wpce_'; // Prefix for all fields
        
		// Only need to initiate the array once per page-load
        if ( ! empty( $this->option_metabox ) ) {
            return $this->option_metabox;
        }        

        // MAIN OPTIONS
		$wpce_main_options = new_cmb2_box( array(
			'id'         => 'wpce-main-options',
			'title'		 => __('General Settings', 'wpce'),
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( 'wpce_main_options' )
			),
		) );
		// Set our CMB2 fields
		
		// CLASS NAMES
		$wpce_main_options->add_field( array(
			'name' => '<h2>' . __( 'Class names', 'wpce' ) . '</h2>',
			'type' => 'title',
			'id'   => $prefix . 'class_names'
		) );
		// scriptClass
		$wpce_main_options->add_field( array(
			'name' => __( 'Script Class', 'wpce' ),
			'desc' => __( 'Add this class and type="text/plain" to every script tag that installs cookies', 'wpce' ),
			'id'   => $prefix . 'script_class',
			'type' => 'text',
			'default' => 'ce-script',
		) );
		// iframeClass
		$wpce_main_options->add_field( array(
			'name' => __( 'Iframe Class', 'wpce' ),
			'desc' => __( 'Add this class and change the src attribute to data-ce-src to every iframe tag that installs cookies', 'wpce' ),
			'id'   => $prefix . 'iframe_class',
			'type' => 'text',
			'default' => 'ce-iframe',
		) );
		// acceptClass
		$wpce_main_options->add_field( array(
			'name' => __( 'Accept Class', 'wpce' ),
			'desc' => __( 'Add this class to any element to make it act as an accept button, enabling the cookies on click.', 'wpce' ),
			'id'   => $prefix . 'accept_class',
			'type' => 'text',
			'default' => 'ce-accept',
		) );
		// dismissClass
		$wpce_main_options->add_field( array(
			'name' => __( 'Dismiss Class', 'wpce' ),
			'desc' => __( 'Add this class to any element to make it act as a dismiss button, removing the notice banner on click.', 'wpce' ),
			'id'   => $prefix . 'dismiss_class',
			'type' => 'text',
			'default' => 'ce-dismiss',
		) );
		// disableClass
		$wpce_main_options->add_field( array(
			'name' => __( 'Disable Class', 'wpce' ),
			'desc' => __( 'Add this class to any element to make it act as a disable button, removing the notice banner and disabling cookies on click.', 'wpce' ),
			'id'   => $prefix . 'disable_class',
			'type' => 'text',
			'default' => 'ce-disable',
		) );
		// HR CLASS
		$wpce_main_options->add_field( array(
			'name' => '<hr /><br />',
			'type' => 'title',
			'id'   => $prefix . 'hr_class'
		) );
		
		// BANNER
		$wpce_main_options->add_field( array(
			'name' => '<h2>' . __( 'Banner settings', 'wpce' ) . '</h2>',
			//'desc' => __( '<hr />', 'wpce' ),
			'type' => 'title',
			'id'   => $prefix . 'banner_title'
		) );
		// bannerClass
		$wpce_main_options->add_field( array(
			'name' => __( 'Banner Class', 'wpce' ),
			'desc' => __( 'This is the class of the banner element that will be created.', 'wpce' ),
			'id'   => $prefix . 'banner_class',
			'type' => 'text',
			'default' => 'ce-banner',
		) );
		// bannerHTML
		$wpce_main_options->add_field( array(
			'name' => __( 'Banner HTML', 'wpce' ),
			'desc' => __( 'Insert the banner content here and give the correct classes to accept, disable and dismiss elements.', 'wpce' ),
			'id'   => $prefix . 'banner_html',
			'type'    => 'wysiwyg',
			'options' => array(
				'wpautop' => false, // use wpautop?
				//'media_buttons' => true, // show insert/upload button(s)
				'textarea_name' => $prefix . 'banner_editor', // set the textarea name to something different, square brackets [] can be used here
				'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
				'tabindex' => '',
				'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
				'editor_class' => '', // add extra class(es) to the editor textarea
				'teeny' => false, // output the minimal editor config used in Press This
				'dfw' => false, // replace the default fullscreen with DFW (needs specific css)
				'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
				'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
			),
			'default' => __( 'This website uses cookies. <a href="#" class="ce-accept">Enable Cookies</a>', 'wpce' ),
		) );
		// HR BANNER
		$wpce_main_options->add_field( array(
			'name' => '<hr /><br />',
			'type' => 'title',
			'id'   => $prefix . 'hr_banner'
		) );
		
		// CONSENT
		$wpce_main_options->add_field( array(
			'name' => '<h2>' . __( 'Consent options', 'wpce' ) . '</h2>',
			//'desc' => __( '<hr />', 'wpce' ),
			'type' => 'title',
			'id'   => $prefix . 'consent_title'
		) );
		// eventScroll
		$wpce_main_options->add_field( array(
			'name' => __( 'Scroll to consent', 'wpce' ),
			'desc' => __( 'This option will enable cookies when the user scrolls a certain amount in any direction.', 'wpce' ),
			'id'   => $prefix . 'event_scroll',
			'type' => 'checkbox',
		) );
		// scrollOffset
		$wpce_main_options->add_field( array(
			'name' => __( 'Scroll Offset', 'wpce' ),
			'desc' => __( 'The height to scroll in pixels.', 'wpce' ),
			'id'   => $prefix . 'scroll_offset',
			'type' => 'text',
			'default' => '200',
		) );
		// clickOutside
		$wpce_main_options->add_field( array(
			'name' => __( 'Click to consent', 'wpce' ),
			'desc' => __( 'This option will enable cookies when the user clicks on any element on the page that doesn\'t belong to the notice banner or other script-created elements.', 'wpce' ),
			'id'   => $prefix . 'click_outside',
			'type' => 'checkbox',
		) );
		// HR CONSENT
		$wpce_main_options->add_field( array(
			'name' => '<hr /><br />',
			'type' => 'title',
			'id'   => $prefix . 'hr_consent'
		) );
		
		// COOKIE
		$wpce_main_options->add_field( array(
			'name' => '<h2>' . __( 'Cookie settings', 'wpce' ) . '</h2>',
			//'desc' => __( '<hr />', 'wpce' ),
			'type' => 'title',
			'id'   => $prefix . 'cookie_title'
		) );
		// cookieName
		$wpce_main_options->add_field( array(
			'name' => __( 'Cookie name', 'wpce' ),
			'desc' => __( 'This is the name of the cookie that is created by this plugin to store the user preference.', 'wpce' ),
			'id'   => $prefix . 'cookie_name',
			'type' => 'text',
			'default' => 'ce-cookie',
		) );
		// cookieDuration
		$wpce_main_options->add_field( array(
			'name' => __( 'Cookie duration', 'wpce' ),
			'desc' => __( 'In days.', 'wpce' ),
			'id'   => $prefix . 'cookie_duration',
			'type' => 'text',
			'default' => '356',
		) );
		// HR COOKIE
		$wpce_main_options->add_field( array(
			'name' => '<hr /><br />',
			'type' => 'title',
			'id'   => $prefix . 'hr_cookie'
		) );
		
		// IFRAME
		$wpce_main_options->add_field( array(
			'name' => '<h2>' . __( 'Iframe settings', 'wpce' ) . '</h2>',
			//'desc' => __( '<hr />', 'wpce' ),
			'type' => 'title',
			'id'   => $prefix . 'iframe_title'
		) );
		// iframesPlaceholder
		$wpce_main_options->add_field( array(
			'name' => __( 'Iframe placeholder', 'wpce' ),
			'desc' => __( 'A custom placeholder for blocked iframes will be inserted after the hidden iframe element.', 'wpce' ),
			'id'   => $prefix . 'iframes_placeholder',
			'type' => 'checkbox',
		) );
		// iframesPlaceholderClass
		$wpce_main_options->add_field( array(
			'name' => __( 'Iframes Placeholder Class', 'wpce' ),
			'desc' => __( 'This is the class of the placeholder element that will be created.', 'wpce' ),
			'id'   => $prefix . 'placeholder_class',
			'type' => 'text',
			'default' => 'ce-iframePlaceholder',
		) );
		// iframesPlaceholderHTML
		$wpce_main_options->add_field( array(
			'name' => __( 'Placeholder HTML', 'wpce' ),
			'desc' => __( 'Insert the placeholder content here and give the correct classes to accept, disable and dismiss elements.', 'wpce' ),
			'id'   => $prefix . 'placeholder_html',
			'type'    => 'wysiwyg',
			'options' => array(
				'wpautop' => false, // use wpautop?
				'media_buttons' => true, // show insert/upload button(s)
				//'textarea_name' => $editor_id, // set the textarea name to something different, square brackets [] can be used here
				'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
				'tabindex' => '',
				'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
				'editor_class' => '', // add extra class(es) to the editor textarea
				'teeny' => false, // output the minimal editor config used in Press This
				'dfw' => false, // replace the default fullscreen with DFW (needs specific css)
				'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
				'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
			),
			'default' => __( 'This content is not available without cookies. <a href="#" class="ce-accept">Enable Cookies</a>', 'wpce' ),
		) );
		// HR IFRAME
		$wpce_main_options->add_field( array(
			'name' => '<hr /><br />',
			'type' => 'title',
			'id'   => $prefix . 'hr_iframe'
		) );
		
		array_push($this->option_metabox, $wpce_main_options->meta_box);
		
		//ASPECT OPTIONS
		$wpce_aspect_options = new_cmb2_box( array(
			'id'         => 'wpce-aspect-options',
			'title'		 => __('Aspect', 'wpce'),
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( 'wpce_aspect_options' )
			),
		) );
		// Banner Poisition
		$wpce_aspect_options->add_field( array(
			'name' => __( 'Banner position', 'wpce' ),
			'desc' => __( 'Position your banner at the top or at the bottom of your page.', 'wpce' ),
			'id'   => $prefix . 'banner_position',
			'type' => 'select',
			'default' => 'top',
			'options'          => array(
				'top' => __( 'Top', 'wpce' ),
				'bottom'   => __( 'Bottom', 'wpce' ),
			),
		) );
		// Text Color
		$wpce_aspect_options->add_field( array(
			'name'    => __( 'Text color', 'wpce' ),
			'desc' => __( 'Text color. Default #ffffff', 'wpce' ),
			'id'      => $prefix . 'text_color',
			'type'    => 'colorpicker',
			'default' => '#ffffff',
		) );
		// Banner Background
		$wpce_aspect_options->add_field( array(
			'name'    => __( 'Banner background', 'wpce' ),
			'desc' => __( 'Banner background color. Default #222222', 'wpce' ),
			'id'      => $prefix . 'banner_background',
			'type'    => 'colorpicker',
			'default' => '#222222',
		) );
		// Other customizations
		$wpce_aspect_options->add_field( array(
			'name' => __( 'Other customizations', 'wpce' ),
			'desc' => __( 'You can further customize the aspect of the banner and his text by using the visual editor in the General Settings tab or typing directly custom CSS code.', 'wpce' ) . '<br /><br /><hr />',
			'type' => 'title',
			'id'   => $prefix . 'other_customizations'
		) );
		// Custom CSS
		$wpce_aspect_options->add_field( array(
			'name' => __( 'Custom CSS', 'wpce' ),
			'desc' => __( 'Type in your custom CSS code. Use class names you specified in General Settings.', 'wpce' ),
			'id'   => $prefix . 'custom_css',
			'type'    => 'textarea',
			'default' => '',
		) );
		
		array_push($this->option_metabox, $wpce_aspect_options->meta_box);
        
        //insert extra tabs here

        return $this->option_metabox;
    }
		
    /**
     * Returns the option key for a given field id
     * @since  0.1.0
     * @return array
     */
    public function get_option_key($field_id) {
    	$option_tabs = $this->option_fields();
    	foreach ($option_tabs as $option_tab) { //search all tabs
    		foreach ($option_tab['fields'] as $field) { //search all fields
    			if ($field['id'] == $field_id) {
    				return $option_tab['id'];
    			}
    		}
    	}
    	return $this->key; //return default key if field id not found
    }

    /**
     * Public getter method for retrieving protected/private variables
     * @since  0.1.0
     * @param  string  $field Field to retrieve
     * @return mixed          Field value or exception is thrown
     */
    public function __get( $field ) {

        // Allowed fields to retrieve
        if ( in_array( $field, array( 'key', 'fields', 'title', 'options_pages' ), true ) ) {
            return $this->{$field};
        }
        if ( 'option_metabox' === $field ) {
            return $this->option_fields();
        }

        throw new Exception( 'Invalid property: ' . $field );
    }

}

/**
 * Helper function to get/return the Wpce_Admin object
 * @since  0.1.0
 * @return Wpce_Admin object
 */
function wpce_admin() {
	static $object = null;
	if ( is_null( $object ) ) {
		$object = new Wpce_Admin();
		$object->hooks();
	}
	return $object;
}

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
function wpce_get_option( $key = '' ) {
	return cmb2_get_option( wpce_admin()->get_option_key($key), $key );
}

// Get it started
wpce_admin();

?>