<?php
/*
Plugin Name: Spazio Sputnik Installer
Plugin URI: https://local.loc
Description: 
Version: 0.1
Author: Dario Candelù
Author URI: http://www.spaziosputnik.it
Text Domain: spaziosputnik-installer
*/
if ( ! defined( 'ABSPATH' ) ) { exit; /* Exit if accessed directly */ }

if ( ! class_exists( 'SpazioSputnik_Installer' ) )
{
	class SpazioSputnik_Installer
	{
		
		function __construct() {
			// add_filter ('pre_set_site_transient_update_plugins', [$this, 'add_update']);
			// add_filter ('pre_set_site_transient_update_plugins', [$this, 'display_transient_update_plugins']);
			add_action( 'admin_init', [$this, 'install_theme'] );
		}
		// update.php?action=install-theme&theme=magazine-style&_wpnonce=74a1d69d5a
		function display_transient_update_plugins ($transient)
		{
			wp_die('<pre>'.var_export($transient, true).'</pre>');
		}

		function add_update ($transient) {
			$obj = new stdClass();
			$obj->slug = 'spaziosputnik-installer.php';
			$obj->new_version = '0.2';
			$obj->url = 'http://www.google.it';
			$obj->package = 'http://anyurl.com';
			$transient->response['spaziosputnik-installer.php'] = $obj;
			return $transient;
		}

		protected $url = 'http://1.shadowcdn.com/files/example-theme-1.0-2014.zip';

		function install_theme () {
			include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

			if ( ! current_user_can( 'upload_themes' ) )
				wp_die( __( 'You do not have sufficient permissions to install themes on this site.' ) );
			// check_admin_referer('theme-upload'); // check nonce

			$response = wp_remote_get( $this->url );
			$message  = wp_remote_retrieve_response_message( $response );
			$code     = wp_remote_retrieve_response_code( $response );
			$mimetype = wp_remote_retrieve_header( $response, 'content-type' );

			if ( 'OK' === $message && 200 === $code && 'application/zip' === $mimetype ) {
				$zipfile = wp_remote_retrieve_body( $response );
				$file_upload = new File_Upload_Upgrader('themezip', 'package');
			}

			wp_die('<pre>'.var_export($response, true).'</pre>');
		}

	}

	new SpazioSputnik_Installer;
}