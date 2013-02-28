<?php
/*
Plugin Name: Easy Redirect IP Address Filter
Plugin URI: http://www.beforesite.com/plugins/easy-redirect-by-ip-address
Description: Easily redirect visitors to another address if their IP address does not match yours. Change the options under Easy Redirect IP Address Filter: <a href="admin.php?page=eri_options_pg">Easy Redirect IP Address Filter</a>
Author: Andrew @ Geeenville Web Design
Version: 1.0
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
$eri_plugname = "Easy Redirect IP";
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
define( 'ERI_WEB_URL',      $eri_the_web_url );
define( 'ERI_NAME',         $eri_plugname );
define( 'ERI_S_NAME',       $eri_plug_shortname );
define( 'ERI_DEFAULT_EMAIL',$eri_the_default_email );
define( 'ERI_VERSION',      '1.0' );
define( 'ERI_PREFIX' ,      'eri_');
/**
 * WP_BLOG_NAME & WP_URL
 * used by easy-sign-up/lib/esu-admin-class.php
 **/
if ( ! defined('WP_BLOG_NAME') )
  define( 'WP_BLOG_NAME',   $eri_the_blog_name );
if ( ! defined('WP_URL') )
  define( 'WP_URL',         $eri_the_web_url );

/**
 * Load included files
 * Class files
*/
include 'lib'.ERIDS.'eri-admin-class.php';
/**
 * Create a new instances for our classes
 **/
$eri_admin    = new EriAdmin();

/* activation and deactivation */
/**
 * Run on activation 
 * */
register_activation_hook( __FILE__, 'eri_activate' );

function eri_activate()
{
  
}

/**
 * Removes the esu options from the database 
 * @since 3.0
 **/
register_deactivation_hook(__FILE__, 'eri_deactivate');
function eri_deactivate()
{
  #if (get_option('easy_sign_up_delete_settings') != true) return; // don't delete
  // remove the plugin's default options from the database
  #delete_option( $eri_options );
}
// load translations - if any
function eri_init() {
  #load_plugin_textdomain( 'eri_lang', false, dirname( plugin_basename( __FILE__ ) )  . '/languages/' );  
}
add_action('plugins_loaded', 'eri_init', 99999);
