<?php
/**
 * @package wordpress-copyright-protector
 * @version 0.1
 */
/*
Plugin Name: Wordpress Copyright Protector
Plugin URI: http://www.example.com
Description: Copyright Copy and Paste Protector
Author: Mike Roberto
Version: 0.1
Author URI: http://www.example.com
Text Domain: wordpress-copyright-protector
*/

define('WPCP_PLUGIN_NAME', plugin_basename(__FILE__));
define('WPCP_PLUGIN_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('WPCP_PLUGIN_VERSION','0.1');

require_once 'classes/settings.php';


if (!class_exists('Wp_Copyright_Protector')) {

	class Wp_Copyright_Protector{
		/**
		 * @var Wp_Copyright_Protector
		 */
		static private $_instance = null;
		
		private $_contactmixcore;
		
		/**
		 * Get Wp_Copyright_Protector object
		 *
		 * @return Wp_Copyright_Protector
		 */
		static public function getInstance()
		{
			if (self::$_instance == null) {
				self::$_instance = new Wp_Copyright_Protector();
			}

			return self::$_instance;
		}


		private function __construct()
		{

			register_activation_hook(WPCP_PLUGIN_NAME, array(&$this, 'pluginActivate'));
			register_deactivation_hook(WPCP_PLUGIN_NAME, array(&$this, 'pluginDeactivate'));
			register_uninstall_hook(WPCP_PLUGIN_NAME, array('contactmix', 'pluginUninstall'));

			
			## Register plugin widgets
			add_action('init', array($this, 'load_transl'));
			add_action('plugins_loaded', array(&$this, 'pluginLoad'));

			add_action( 'widgets_init', array(&$this, 'widgetsRegistration') );
			
			if (is_admin()) {
			add_action('wp_print_scripts', array(&$this, 'adminLoadScripts'));
			add_action('wp_print_styles', array(&$this, 'adminLoadStyles'));
			}
			else{

			add_action('wp_print_scripts', array(&$this, 'siteLoadScripts'));
			add_action('wp_print_styles', array(&$this, 'siteLoadStyles'));


			}

			add_action( 'wp_footer',array(&$this, 'footerScript'));

		}

		public function load_transl()
		{
			load_plugin_textdomain('wordpress-copyright-protector', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
		}

		##
		## Loading Scripts and Styles
		##
	
		public function adminLoadStyles()
		{
            	  

		}
	
		public function adminLoadScripts(){
		  
		}
	
	
	
		public function siteLoadStyles(){
			
	
		}
	
	
		public function siteLoadScripts(){
		
	
			
			$wpcp_general_settings = get_option('wpcp_general_settings');
			
			$wpcp_text_before_url = (!empty($wpcp_general_settings['wpcp_text_before_url'])?$wpcp_general_settings['wpcp_text_before_url']:'');
			
			$wpcp_disable_admin = (!empty($wpcp_general_settings['wpcp_disable_admin'])?$wpcp_general_settings['wpcp_disable_admin']:'');
			
			$enable_script = true;
			if ( is_super_admin() && $wpcp_disable_admin == "on" ) {
				$enable_script = false;
			}
			
			if($enable_script){
				wp_enqueue_script( 'jquery' );
				
				wp_enqueue_script(
						'wpcp-script',
						plugins_url('js/wpcp-script.js', __FILE__),
						array('jquery')
				);
				
				
				$wpcp_config = array('wpcp_text_before_url'=>$wpcp_text_before_url,'enable_script'=>$enable_script);
					
					
				wp_localize_script( 'wpcp-script', 'wpcp_config',$wpcp_config );
				
				
			}
			
			
			
			
		}



		##
		## Widgets initializations
		##

		public function widgetsRegistration(){
		  		 
		 		 
		}


		
		##
		## Plugin Activation and Deactivation
		##

		/**
		* Activate plugin
		* @return void
		*/
		public function pluginActivate()
		{ 
			
			$settings_general = get_option('wpcp_general_settings');
			
			if(empty($settings_general['wpcp_text_before_url'])){
				$settings_general['wpcp_text_before_url'] = 'Read more at:';
			}
			
			update_option('wpcp_general_settings', $settings_general);
		}

		/**
		* Deactivate plugin
		* @return void
		*/
		public function pluginDeactivate(){
			
		}

		/**
		* Uninstall plugin
		* @return void
		*/
		static public function pluginUninstall()
		{

		}


		public function pluginLoad(){

		}
		
		public function footerScript(){

       
		}	  

		}

}



//instantiate the class
if (class_exists('Wp_Copyright_Protector')) {
	$Wp_Copyright_Protector =  Wp_Copyright_Protector::getInstance();
}

