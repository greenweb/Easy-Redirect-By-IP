<?php

if (!class_exists("EriAdmin")) {
	class EriAdmin
	{
		function __construct()
		{
			add_action( 'init',array($this,'eri_redirect') );
		}

    function eri_redirect()
    {
      if(is_admin()) return;
      if($_SERVER['REMOTE_ADDR'] !== '71.12.12.226' && $_GET['safe'] !== 'rew'){
        wp_redirect( "http://www.greenvilleweb.us", 302 );
      }
    
    }
	} //End Class EasyAdmin

} //End if Class EasyAdmin

// eof