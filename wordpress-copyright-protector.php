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
			wp_enqueue_script( 'jquery' );			  
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

                $wpcp_general_settings = get_option('wpcp_general_settings');
                
                $wpcp_text_before_url = (!empty($wpcp_general_settings['wpcp_text_before_url'])?$wpcp_general_settings['wpcp_text_before_url']:'Read more at');
                ?>
                <script type="text/javascript">

            
                jQuery("body").bind('copy', function (e) {
                        if (typeof window.getSelection == "undefined") return; //IE8 or earlier...
                        
                        var body_element = document.getElementsByTagName('body')[0];
                        var selection = window.getSelection();
                        
                        //if the selection is short let's not annoy our users
                        if (("" + selection).length < 30) return;
                
                        //create a div outside of the visible area
                        //and fill it with the selected text
                        var newdiv = document.createElement('div');
                        newdiv.style.position = 'absolute';
                        newdiv.style.left = '-99999px';
                        body_element.appendChild(newdiv);
                        newdiv.appendChild(selection.getRangeAt(0).cloneContents());
                        
                        //we need a <pre> tag workaround
                        //otherwise the text inside "pre" loses all the line breaks!
                        if (selection.getRangeAt(0).commonAncestorContainer.nodeName == "PRE") {
                                newdiv.innerHTML = "<pre>" + newdiv.innerHTML
                                + "</pre><br /><?php echo $wpcp_text_before_url; ?>: <a href='" + document.location.href + "'>"
                                + document.location.href + "</a> &copy; MySite.com";
                        }
                        else
                                newdiv.innerHTML += "<br /><br /><?php echo $wpcp_text_before_url; ?>: <a href='"
                                + document.location.href + "'>"
                                + document.location.href + "</a> &copy; MySite.com";
                                        
                        selection.selectAllChildren(newdiv);
                        window.setTimeout(function () { body_element.removeChild(newdiv); }, 200);
                });    
                </script>
                <?php
		}	  

		}

}



//instantiate the class
if (class_exists('Wp_Copyright_Protector')) {
	$Wp_Copyright_Protector =  Wp_Copyright_Protector::getInstance();
}

