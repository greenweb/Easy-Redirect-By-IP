<?php
if (!class_exists("EriRedirect")) {
  class EriRedirect
  {
    function __construct()
    {
      add_action( 'wp_loaded',array($this,'eri_redirect') );
    }

    function eri_redirect()
    {
      if(is_admin()) return;
      $eri_redirect_to_url = get_option( 'eri_redirect_to_url' );
      $eri_safe_ips = get_option( 'eri_safe_ips', FALSE );
      $eri_pass_key = get_option( 'eri_pass_key', FALSE );
      $get_pass_key = (isset($_REQUEST['epk'])) ? $_REQUEST['epk'] : FALSE ; // short end to make it easier for the user.
      if ($get_pass_key !== FALSE && $get_pass_key === $eri_pass_key) {
        if( !in_array($_SERVER['REMOTE_ADDR'], $eri_safe_ips)  ){
          // add ip to db
          $eri_safe_ips[] = $_SERVER['REMOTE_ADDR'];
          update_option('eri_safe_ips', $eri_safe_ips);
        }
        return;
      }

      if( !in_array($_SERVER['REMOTE_ADDR'], $eri_safe_ips)  ){
        if ( $eri_redirect_to_url != FALSE ) {
          wp_redirect( $eri_redirect_to_url, 302 );
        }else{
          wp_die( __( '<h1 style="text-align:center">You are not authorized to view this website</h1>','eri_lang' ), __( 'Access denied','eri_lang' ) );
        }
      }
    }

  } //End Class EasyAdmin

} //End if Class EasyAdmin

class eriOptions {
  public function __construct() {
    if ( is_admin() ) {
      add_action( 'admin_menu', array( $this, 'add_eri_plugin_page' ) );
      add_action( 'admin_init', array( $this, 'page_init' ) );
      add_action( 'admin_enqueue_scripts', array( $this, 'eri_load_tag_styles' ) );
    }
  }

  function eri_load_tag_styles($hook) {
    if($hook !=='settings_page_eri-options-page') return;
    wp_register_style( 'eri_tag_styles', ERI_URL . 'css/jquery.tagsinput.css', false, '1.3.3' );
    wp_enqueue_style( 'eri_tag_styles' );
    wp_enqueue_script( 'tagsinput', ERI_URL . 'js/jquery.tagsinput.min.js', array('jquery'), '1.3.3', false );
    wp_enqueue_script( 'eri_js', ERI_URL . 'js/eri.js', array('tagsinput'), ERI_VERSION, false );
  }

  public function add_eri_plugin_page() {
    // This page will be under "Settings"
    $eri_page_name = __( 'Redirect by IP','eri_lang' );
    add_options_page( $eri_page_name, $eri_page_name , 'manage_options', 'eri-options-page', array( $this, 'create_eri_admin_page' ) );
  }

  public function create_eri_admin_page() {
    ?>
    <div class="wrap">
        <?php $eri_page_name = __( 'Easy Bouncer Settings','eri_lang' ); ?>
        <h2><?php echo $eri_page_name ; ?></h2>
        <?php $this->eri_print_self_promotion(); ?>
        <form method="post" action="options.php">
            <?php
              // This print out all hidden setting fields
              settings_fields( 'eri_option_group' );
              do_settings_sections( 'eri-options-page' );
            ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
  }

  public function page_init() {
    register_setting( 'eri_option_group', 'eri_option', array( $this, 'eri_sanitize' ) );

    add_settings_section(
      'setting_section_id',
      __('Setting', 'eri_lang'),
      array( $this, 'eri_print_section_info' ),
      'eri-options-page'
    );

    add_settings_field(
      'pass_key',
      __('Numeric Passkey', 'eri_lang'),
      array( $this, 'eri_pass_key_input' ),
      'eri-options-page',
      'setting_section_id'
    );

    add_settings_field(
      'redirect_url',
      __( 'Redirection URL', 'eri_lang' ),
      array( $this, 'eri_redirect_to_url_input' ),
      'eri-options-page',
      'setting_section_id'
    );

    add_settings_section(
      'current_ips_section',
      __('Safe IPs Addresses', 'eri_lang'),
      array( $this, 'eri_print_current_ips_info' ),
      'eri-options-page'
    );

  }

  public function eri_sanitize( $input ) {
    // PassKey
    if(isset($input)){
      extract($input);
    }

    if ( isset($pass_key) && is_numeric( $pass_key ) ) {
      if ( get_option('eri_pass_key') === FALSE ) {
        add_option('eri_pass_key', $pass_key);
      }else {
        update_option('eri_pass_key', $pass_key);
      }
    }
    // Redirection URL
    $redirect_to_url = (isset($redirect_to_url)) ? esc_url_raw( $redirect_to_url, array( 'http', 'https') ) : null ;
    if ( $redirect_to_url !== null ) {
      $redirect_to_url_host = parse_url($redirect_to_url, PHP_URL_HOST);
      $this_urls_host = parse_url(ERI_URL, PHP_URL_HOST);

      if ($redirect_to_url_host == $this_urls_host ) {
        $url_error  = '<h1 style="text-align:center; color:red">';
        $url_error .= __( "Opps infinite loop warning!",'eri_lang' );
        $url_error .= "</h1>";
        $url_error .= "<p>";
        $url_error .= __( "Don't set the redirect to a page on this website or you'll end up with a infinite loop!",'eri_lang' );
        $url_error .= "</p>";
        $url_error .= "<p>";
        $url_error .= __( "Use the link or your browser's back button and change the redirection URL",'eri_lang' );
        $url_error .= "</p>";


        wp_die( $url_error , __( 'Error: infinite loop!','eri_lang' ), array('back_link'=>true) );
      }
    }

    if ( isset($redirect_to_url) &&  filter_var($redirect_to_url, FILTER_VALIDATE_URL) !== FALSE) {
      $redirect_to_url = esc_url_raw($redirect_to_url, array( 'http', 'https') ); // sanitze using wp function esc_url_raw( $url, $protocols );
      if ( get_option('eri_redirect_to_url') === FALSE ) {
        add_option('eri_redirect_to_url', $redirect_to_url);
      }else {
        update_option('eri_redirect_to_url', $redirect_to_url);
      }
    }
    // ip address
    $eri_safe_ips_array = explode(',', $input['eri_safe_ips']);
    $eri_save_ips_array = array();
    foreach ($eri_safe_ips_array as $key => $ip_a) {
      if (filter_var($ip_a, FILTER_VALIDATE_IP)) {
        $eri_save_ips_array[] = $ip_a;
      }
    }
    $users_ip = $_SERVER['REMOTE_ADDR'];
    if( !in_array($users_ip, $eri_save_ips_array) ) {
      $eri_save_ips_array[] = $users_ip;
    }
    update_option('eri_safe_ips', $eri_save_ips_array);

  }

  public function eri_print_section_info() {
    print __( 'Choose a passkey to automatic add an IP address to the safe list.','eri_lang' );
    print '<br>';
    print __( 'This will be useful if you need to give a Client access to the website or if your IP address changes.','eri_lang' );
  }

  public function eri_pass_key_input() {
    $eri_pass_key  = get_option('eri_pass_key');
    if($eri_pass_key == ""){
      $eri_pass_key = rand(100, 99999);
    }
    ?>
    <input type="text" id="eri_pass_key" name="eri_option[pass_key]" value="<?php echo $eri_pass_key;?>" >
    <?php
        echo '<p class="description">';
          _e( 'This must a number with no gaps or special characters.', 'eri_lang' );
        echo '<br>';
          _e( 'Use this URL to add a viewers IP address to your safe list:', 'eri_lang' );
        echo '<p>';
        echo '<code >';
        echo site_url('/?epk=').get_option('eri_pass_key');
        echo '</code>';
  }

  public function eri_redirect_to_url_input() {
    ?>
    <input type="text" id="eri_redirect_to_url" name="eri_option[redirect_to_url]" value="<?php 
                                                                                              echo esc_url_raw( get_option('eri_redirect_to_url'), array( 'http', 'https') );
                                                                                          ?>" class="regular-text code">
    <?php
      echo '<p class="description">';
      _e( 'This must be a valid url including the <code>http://</code> or <code>https://</code>', 'eri_lang' );
      echo '<p>';
  }
  public function eri_print_current_ips_info() {
    $eri_safe_ips = get_option('eri_safe_ips');
    echo("<input type='text' id='eri_safe_ips_input' class='tags' name='eri_option[eri_safe_ips]' value='");
    foreach ($eri_safe_ips as $key => $ip) {
      echo($ip.',');
    }
    echo("'>");
  }

  public function eri_print_self_promotion()
  { ?>
    <!-- UpGrade -->
    <div id="eri_like_plug">
      <h3><?php _e('This plugin has cost me many hours of work, if you use it, please:','eri_lang'); ?></h3>
        <ol>
          <li>
            <a href="http://wordpress.org/support/view/plugin-reviews/easy-redirect-by-ip"><?php echo __('Rate the plugin <span title="Five Stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span> on WordPress.org','eri_lang'); ?></a>
          </li>
          <li>
            <?php _e("Need WordPress help?",'eri_lang'); ?> 
              <a href="http://www.greenvilleweb.us/services/?ref=ehb_plugin_services" 
                title="<?php _e("Need WordPress help?",'eri_lang'); ?>"><span>&#9733;</span><?php _e("Hire me for custom Themes and Plugins",'eri_lang'); ?><span>&#9733;</span></a>
          </li>
        </ol>
    </div>  
    <!-- END Upgrade -->
  <?php
  }

}
// eof