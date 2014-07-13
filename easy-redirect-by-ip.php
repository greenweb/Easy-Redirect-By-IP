<?php
/*
Plugin Name: Easy Bouncer - Redirect by IP
Plugin URI: http://www.beforesite.com/plugins/easy-redirect-by-ip-address
Description: Easily redirect visitors to another web address if their IP address is not on a safe list. Change the options under <em>Dashboard > Setting >> <a href="options-general.php?page=eri-options-page">Redirect IP</a></em>
Author: Andrew @ Geeenville Web Design
Version:1
Author URI: http://www.beforesite.com
License: GPLv2 or later
*/
if (!function_exists ('add_action')){
  header('Status: 403 Forbidden');
  header('HTTP/1.1 403 Forbidden');
  exit();
}
/**
 * Register Globals
 * */
$eri_plugin_loc = plugin_dir_url( __FILE__ );
$eri_plugname = "Easy Filter and Redirect by IP";
$eri_plug_shortname = "easy_redirect_ip";
$eri_the_web_url = home_url();
$eri_the_blog_name = get_bloginfo('name');
$eri_the_default_email = get_bloginfo('admin_email');
if ( preg_match( '/^https/', $eri_plugin_loc ) && !preg_match( '/^https/', home_url() ) )
  $eri_plugin_loc = preg_replace( '/^https/', 'http', $eri_plugin_loc );
/**
 * Define Globals
 * */
define( 'ERI_FRONT_URL',    $eri_plugin_loc );
define( 'ERIDS',            DIRECTORY_SEPARATOR );
define( 'ERI_URL',          plugin_dir_url(__FILE__) );
define( 'ERI_PATH',         plugin_dir_path(__FILE__) );
define( 'ERI_BASENAME',     plugin_basename( __FILE__ ) );
define( 'ERI_NAME',         $eri_plugname );
define( 'ERI_VERSION',      '1' );
define( 'ERI_PREFIX' ,      'eri_');

/**
 * Load included files
 * Class files
*/
include 'lib'.ERIDS.'eri-admin-class.php';
/**
 * Create a new instances for our classes
 **/
$eri_redirect = new EriRedirect();
$eri_options  = new eriOptions();

/* activation and deactivation */
/**
 * Run on activation 
 * */
register_activation_hook( __FILE__, 'eri_activate' );

function eri_activate()
{
  $eri_set_ipaddress        = $_SERVER['REMOTE_ADDR'];
  $eri_set_ipaddress_array  = array($eri_set_ipaddress);
  $eri_safe_ips             = add_option( 'eri_safe_ips', $eri_set_ipaddress_array ); 
  $eri_pass_key             = add_option( 'eri_pass_key', rand(100, 99999) );
  $eri_set_transient        = __("Your IP address <b>$eri_set_ipaddress</b> has been added to the safe list.", 'eri_lang');
  set_transient( 'eri_update_message', $eri_set_transient, 1 * MINUTE_IN_SECONDS);
}

/**
* Set the admin message - last for 1 minute
*/
add_action('admin_notices', 'eri_get_transient_message');
function eri_get_transient_message()
{
  if ( ! current_user_can('activate_plugins') ) return;
  $eri_update_message = get_transient( 'eri_update_message' );
  if (false  === $eri_update_message) return;

  $left_link_text = __("Please have a look at the plugin's options page", 'eri_lang');
  global $blog_id;
  $eri_options_page_url = get_admin_url( $blog_id, '/options-general.php?page=eri-options-page');
  $left_link = '<a href="'.$eri_options_page_url.'">'.$left_link_text.'</a>';
  echo ("<div id='eri-message' class='updated'><p>$eri_update_message <span style='float:right'>$left_link</span></p></div>");
}


/**
 * Removes the eri options from the database 
 * @since 3.0
 **/
register_deactivation_hook(__FILE__, 'eri_deactivate');
function eri_deactivate()
{
  // remove the plugin's default options from the database
  delete_option( 'eri_safe_ips' );  
  delete_option( 'eri_pass_key' );  
  delete_option( 'eri_option' );  
  delete_option( 'eri_redirect_to_url' );  
}
// load translations - if any
function eri_init() {
  load_plugin_textdomain( 'eri_lang', false, dirname( plugin_basename( __FILE__ ) )  . '/languages/' );  
}
add_action('plugins_loaded', 'eri_init', 99999);
