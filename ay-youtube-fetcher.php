<?php
/**
  * Plugin Name: AY Youtube Fetcher
  * Plugin URI: https://github.com/adityayuga/ay-youtube-fetcher
  * Description: This plugin fetch youtube videos from your youtube account
  * Version: 1.0.0
  * Author: Aditya Yuga Pradhana
  * Author URI: https://github.com/adityayuga/
  * License: MIT
  */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_menu', 'ay_youtube_fetcher_menu');

function ay_youtube_fetcher_menu() {
	add_menu_page('Youtube Fetcher Options', 'Youtube Fetcher', 'manage_options', 'ay-youtube-fetcher', 'ay_youtube_fetcher_options');
	//add_options_page( 'Youtube Fetcher Options', 'Youtube Fetcher', 'manage_options', 'ay-youtube-fetcher', 'ay_youtube_fetcher_options');
	add_action( 'load-' . $hook_suffix , 'ay_youtube_fetcher_load' );

	//call register settings function
	add_action( 'admin_init', 'ay_youtube_fetcher_plugin_settings' );
}

function ay_youtube_fetcher_plugin_settings() {
	//register our settings
	register_setting( 'ay-youtube-fetcher-settings-group', 'google_api_key' );
	register_setting( 'ay-youtube-fetcher-settings-group', 'youtube_channel_id' );
	register_setting( 'ay-youtube-fetcher-settings-group', 'max_result' );
}

function ay_youtube_fetcher_options() {
	if( !current_user_can('manage_options')) {
		wp_die( __('You do not have sufficient permissions to access this page.'));
	}
	
	ob_start();
	?>
		<div class="wrap">
			<h2>AY Youtube Fetcher Setting</h2>
			<form method="post" action="options.php">
			<?php 
				settings_fields( 'ay-youtube-fetcher-settings-group' );
    			do_settings_sections( 'ay-youtube-fetcher-settings-group' ); 
    		?>
		    <table class="form-table">
		        <tr valign="top">
			        <th scope="row">Google API Key</th>
			        <td><input type="text" name="google_api_key" value="<?php echo esc_attr( get_option('google_api_key') ); ?>" /></td>
		        </tr>
		         
		        <tr valign="top">
			        <th scope="row">Youtube Channel ID</th>
			        <td><input type="text" name="youtube_channel_id" value="<?php echo esc_attr( get_option('youtube_channel_id') ); ?>" /></td>
		        </tr>

		        <tr valign="top">
			        <th scope="row">Max Result</th>
			        <td><input type="number" min="0" name="max_result" value="<?php echo esc_attr( get_option('max_result') ); ?>" /></td>
		        </tr>
		    </table> 
			<?php
				submit_button();
			?>
			</form>
		</div>
	<?php

	$content = ob_get_clean();

	echo $content;
}

function ay_youtube_fetcher_load() {
	// Current admin page is the options page for our plugin, so do not display the notice
	// (remove the action responsible for this)
	remove_action( 'admin_notices', 'ay_youtube_fetcher_admin_notices' );
}

add_action( 'admin_notices', 'ay_youtube_fetcher_admin_notices' );

function ay_youtube_fetcher_admin_notices() {
	echo "<div id='notice' class='updated fade'><p>Ay Youtube Fetcher is not configured yet. Please do it now.</p></div>\n";
}

function ay_youtube_fetcher_get_data() {
	$url = 'https://www.googleapis.com/youtube/v3/search?key={your_key_here}&channelId={channel_id_here}&part=snippet,id&order=date&maxResults=20';
}