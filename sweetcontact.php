<?php
/*
Plugin Name: sweetContact Form
Plugin URI: http://sweetcaptcha.com/
Description: sweetContact Form for WordPress. Form builder to let your visitors send you emails.
Version: 1.0 2014-11-18 16:30
Author: Sweet Captcha
Author URI: http://www.sweetcaptcha.com
License: GNU GPL2
*/

//do not allow direct access
if ( strpos(strtolower($_SERVER['SCRIPT_NAME']),strtolower(basename(__FILE__))) ) {
 header('HTTP/1.0 403 Forbidden');
 exit('Forbidden');
}

/********************
 * Global constants
 ********************/
define( 'SWCF_VERSION', '1.0' );
define( 'SWCF_BUILD', '1.0');		// Used to force load of latest .js files
define( 'SWCF_FILE', __FILE__ );	               // /path/to/wp-content/plugins/sweetcontact/sweetcontact.php
define( 'SWCF_PATH', plugin_dir_path(__FILE__) );  // /path/to/wp-content/plugins/sweetcontact/
define( 'SWCF_URL', plugin_dir_url( __FILE__ ) );  // http://www.yoursite.com/wp-content/plugins/sweetcontact/
define( 'SWCF_INCLUDES', SWCF_URL . 'includes' );  
define( 'SWCF_ADMIN_URL', admin_url( 'plugins.php?page=sweetcontact')); // TODO: We need this ?
define( 'SWCF_PLUGIN_NAME', 'sweetContact Form' ); // Use this where possible !
define( 'SWCF_CAPTCHA_PATH', SWCF_PATH . 'captcha');
define( 'SWCF_ATTACH_DIR', SWCF_PATH . 'attachments/' );
define( 'SWCF_MAX_SLUG_LEN', 40 );

define('SWCF_NOT_READY', __( 'Your sweetContact form is not ready yet, click <a href="admin.php?page=sweetcontact">here</a> to configure.', 'sweetcontact' ));

// Set constants for standard field numbers
define( 'SWCF_NAME_FIELD', '1' );
define( 'SWCF_EMAIL_FIELD', '2' );
define( 'SWCF_SUBJECT_FIELD', '3' );
define( 'SWCF_MESSAGE_FIELD', '4' );

global $swcf_special_slugs;		// List of reserve slug names
$swcf_special_slugs = array( 'f_name', 'm_name', 'mi_name', 'l_name', 'email2', 'mailto_id', 'subject_id' );

require_once SWCF_PATH . 'sweetcaptcha.php';	

require_once SWCF_PATH . 'includes/class-sweetcf-utils.php';
require_once SWCF_PATH . 'includes/class-sweetcf-display.php';
require_once SWCF_PATH . 'includes/class-sweetcf-process.php';

require_once SWCF_PATH . 'includes/class-sweetcf-options.php';
if ( is_admin() ) {
	require_once SWCF_PATH . 'includes/class-sweetcf-action.php';	
	require_once( ABSPATH . "wp-includes/pluggable.php" );
}

// Initialize plugin settings and hooks
SWEETCF_Utils::setup();

//add_action('wp_loaded', 'sweetcontact_plugins_loaded');
if (is_admin()) {
  require_once SWCF_PATH . '/sweetcontact_admin.php';
  // Add admin notices.
  add_action('admin_notices', 'sweetcontact_admin_notices');
  // add link to settings menu
  //add_action('admin_menu', 'sweetcaptcha_admin_menu');
} else {
	add_action('wp_loaded', 'sweetcontact_plugins_loaded');
}

function sweetcontact_plugins_loaded() {
	if ( defined('SWCF_SWEETCAPTCHA_PROBLEM') ) { return; }
	define( 'SWCF_SWEETCAPTCHA_OK', (function_exists('sweetcontact_sweetcaptcha_is_registered') && sweetcontact_sweetcaptcha_is_registered()) );
	$sweetcaptcha_problem = '';
	if ( !SWCF_SWEETCAPTCHA_OK ) {
		$sweetcaptcha_problem = SWCF_NOT_READY;
	}
	define( 'SWCF_SWEETCAPTCHA_PROBLEM', $sweetcaptcha_problem );
	
	//echo '<hr>SWCF_SWEETCAPTCHA_PROBLEM: '.SWCF_SWEETCAPTCHA_PROBLEM.'<hr>';
	/*
	if ( SWCF_SWEETCAPTCHA_PROBLEM ) {
		wp_enqueue_style( 'wp-pointer' ); wp_enqueue_script( 'jquery-ui' ); wp_enqueue_script( 'wp-pointer' ); wp_enqueue_script( 'utils' );
		SWEETCF_Utils::add_admin_notice('swcf-sweetcaptcha-problem',SWCF_SWEETCAPTCHA_PROBLEM, 'error ', 'color: red; font-size: 14px; font-weight: bold;	text-align: center;');
		add_action('admin_notices', 'sweetcontact_popup_setup');
	}
	 * 
	 */
}

?>