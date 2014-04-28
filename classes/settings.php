<?php

class Settings_API_Tabs_WPCP_Plugin {
	
	/*
	 * For easier overriding we declared the keys
	 * here as well as our tabs array which is populated
	 * when registering settings
	 */
	private $wpcp_general_settings_key = 'wpcp_general_settings';	
	private $plugin_options_key = 'wpcp_plugin_options';
	private $plugin_settings_tabs = array();
	
	
	/*
	 * Fired during plugins_loaded (very very early),
	 * so don't miss-use this, only actions and filters,
	 * current ones speak for themselves.
	 */
	function __construct() {
		
		add_action( 'init', array( &$this, 'load_settings' ) );
		add_action( 'admin_init', array( &$this, 'register_wpcp_general_settings' ) );		
		add_action( 'admin_menu', array( &$this, 'add_admin_menus' ) );
		
		add_filter( 'plugin_action_links_'.WPCP_PLUGIN_NAME, array( &$this, 'pluginSettingsLink' ) );
		
		
		
	}
	
	/*
	 * Loads both the general and advanced settings from
	 * the database into their respective arrays. Uses
	 * array_merge to merge with default values if they're
	 * missing.
	 *
	 * To get settings, use a new ContactMixCore class and
	 *  call getGeneralOptions and getAdvancedOptions from there
	 */
	function load_settings() {
		$this->wpcp_general_settings = (array) get_option( $this->wpcp_general_settings_key );
		
		// Merge with defaults
		//$this->wpcp_general_settings = array_merge( array(
		//	'general_option' => 'General value'
		//), $this->wpcp_general_settings );

	}
	
	/*
	 * Registers the general settings via the Settings API,
	 * appends the setting to the tabs array of the object.
	 */
	function register_wpcp_general_settings() {
		$this->plugin_settings_tabs[$this->wpcp_general_settings_key] = __('General','wordpress-copyright-protector');
		
		register_setting( $this->wpcp_general_settings_key, $this->wpcp_general_settings_key );
		add_settings_section( 'wpcp_section_general',__('Settings','wordpress-copyright-protector'), array( &$this, 'wpcp_section_general_desc' ), $this->wpcp_general_settings_key );
		add_settings_field( 'wpcp_disable_admin',__('Disable for admins','wordpress-copyright-protector') , array( &$this, 'field_wpcp_disable_admin' ), $this->wpcp_general_settings_key, 'wpcp_section_general' );
                add_settings_field( 'wpcp_text_before_url',__('Text to add before the URL','wordpress-copyright-protector') , array( &$this, 'field_wpcp_text_before_url' ), $this->wpcp_general_settings_key, 'wpcp_section_general' );

	}

	
	
	
	/*
	 * The following methods provide descriptions
	 * for their respective sections, used as callbacks
	 * with add_settings_section
	 */
	function wpcp_section_general_desc() { }
	
	
	
	function field_wpcp_disable_admin() {
		$wpcp_disable_admin = (isset($this->wpcp_general_settings['wpcp_disable_admin'])?esc_attr( $this->wpcp_general_settings['wpcp_disable_admin'] ):'');
		?>
			<input type="checkbox" name="<?php echo $this->wpcp_general_settings_key; ?>[wpcp_disable_admin]"  <?php checked( $wpcp_disable_admin, 'on'); ?> />
			
			<?php
	}		
	
        
        function field_wpcp_text_before_url(){
		$wpcp_text_before_url = (isset($this->wpcp_general_settings['wpcp_text_before_url'])?esc_attr( $this->wpcp_general_settings['wpcp_text_before_url'] ):'');
		?>
			<input type="text" name="<?php echo $this->wpcp_general_settings_key; ?>[wpcp_text_before_url]" value="<?php echo $wpcp_text_before_url; ?>" />
			
			<?php            
            
        }
        
	/*
	 * Called during admin_menu, adds an options
	 * page under Settings called My Settings, rendered
	 * using the plugin_options_page method.
	 */
	function add_admin_menus() {
		add_options_page(__('Copyright Copy and Paste Protector','wordpress-copyright-protector'),__('Copyright Copy and Paste Protector','wordpress-copyright-protector'), 'manage_options', $this->plugin_options_key, array( &$this, 'plugin_options_page' ) );
	}
	
	
	#
	# Plugin Settings link
	#

	public function pluginSettingsLink($links){
	   $settings_link = '<a href="options-general.php?page='.$this->plugin_options_key.'.php">'.__('Settings').'</a>'; 
	   array_unshift($links, $settings_link); 
	  return $links; 
	}
	
	
	/*
	 * Plugin Options page rendering goes here, checks
	 * for active tab and replaces key with the related
	 * settings key. Uses the plugin_options_tabs method
	 * to render the tabs.
	 */
	function plugin_options_page() {
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->wpcp_general_settings_key;
		?>
		<div class="wrap">
			<?php $this->plugin_options_tabs(); ?>
			<form method="post" action="options.php">
				<?php wp_nonce_field( 'update-options' ); ?>
				<?php settings_fields( $tab ); ?>
				<?php do_settings_sections( $tab ); ?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
	
	/*
	 * Renders our tabs in the plugin options page,
	 * walks through the object's tabs array and prints
	 * them one by one. Provides the heading for the
	 * plugin_options_page method.
	 */
	function plugin_options_tabs() {
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->wpcp_general_settings_key;

		screen_icon();
		echo '<h2 class="nav-tab-wrapper">';
		foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->plugin_options_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';	
		}
		echo '</h2>';
	}
};

// Initialize the plugin
add_action( 'plugins_loaded', create_function( '', '$settings_api_tabs_wpcp_plugin = new Settings_API_Tabs_WPCP_Plugin;' ) );