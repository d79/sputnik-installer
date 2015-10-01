<?php
/*
Plugin Name: Sputnik Installer
Plugin URI: https://github.com/d79/sputnik-installer
Description: Gestione installazione e aggiornamento temi e plugin Wordpress di Spazio Sputnik
Version: 0.1.4
Author: Dario Candelù
Author URI: http://www.spaziosputnik.it
Text Domain: sputnik-installer
*/
namespace SSI;

if ( ! defined( 'ABSPATH' ) ) { exit; /* Exit if accessed directly */ }

class Sputnik_Installer
{
	
	private $key;
	private $apiUrl = 'http://api-wp.spaziosputnik.it/';
	public $prefix = 'sputnik-installer-';
	public $pluginsDir;

	function init() {
		include_once plugin_dir_path( __FILE__ ).'inc/gestione-temi.php';
		include_once plugin_dir_path( __FILE__ ).'inc/gestione-plugin.php';
		include_once plugin_dir_path( __FILE__ ).'inc/YahnisElsts/class-theme-update-checker.php';
		include_once plugin_dir_path( __FILE__ ).'inc/YahnisElsts/plugin-updates/plugin-update-checker.php';
		$this->key = $this->getKey();
		$this->pluginsDir = realpath(dirname(__FILE__).'/../') . '/';
		Temi\set_update_checker();
		Plugin\set_update_checker();
		$this->check4update();
		add_action( 'admin_menu', array($this, 'menu') );
		add_action( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'link2settings') );
		if(isset($_POST['do-ssi-theme-install']))
			add_action( 'admin_init', 'SSI\\Temi\\installa_tema' );
		elseif(isset($_POST['do-ssi-save-key']))
			add_action( 'admin_init', array($this, 'saveKey') );
		elseif(isset($_POST['do-ssi-delete-key']))
			add_action( 'admin_init', array($this, 'deleteKey') );
		elseif(isset($_POST['do-ssi-plugin-install']))
			add_action( 'admin_init', 'SSI\\Plugin\\installa_plugin' );
		// cron jobs
		add_action( 'cron_giornaliero_hook', 'SSI\\Temi\\lista_temi' );
		add_action( 'cron_giornaliero_hook', 'SSI\\Plugin\\lista_plugin' );
	}

	function menu () {
		$hook_suffix = add_submenu_page(
			'tools.php',
			'Sputnik Installer',
			'Sputnik Installer',
			'upload_themes',
			'sputnik-installer',
			array($this, 'page')
		);
		add_action( 'load-' . $hook_suffix, array($this, 'js') );
	}

	function page () {
		$key          = $this->key;
		$temi         = Temi\lista_temi();
		$plugins      = Plugin\lista_plugin();
		$installed    = $this->pluginsInstalled($plugins);
		$plugins_path = $this->pluginsDir;
		include plugin_dir_path( __FILE__ ).'dashboard/page.php';
		Temi\check_now();
	}

	function link2settings( $links ) {
		array_unshift( $links, sprintf('<a href="%s">%s</a>', menu_page_url( 'sputnik-installer', false ), __('Settings') ) );
		return $links;
	}

	function js () {
		wp_enqueue_script( 'ssi_scripts', plugin_dir_url( __FILE__ ) . 'dashboard/js/scripts.js', array('jquery') );
	}

	function fetch_json ( $url, $args = array() ) {
		$response = wp_remote_get( $url, $args );
		if((!is_wp_error($response)) && 200 == wp_remote_retrieve_response_code($response))
			if(!is_null($obj = json_decode(wp_remote_retrieve_body($response))))
				return $obj;
		return false;
	} // fetch_json

	function getKey() {
		return get_option( $this->prefix.'key' );
	}

	function saveKey () {
		if(isset($_POST['key']) && check_admin_referer('ssi-save-key')) {
			$this->key = sanitize_text_field( $_POST['key'] );
			update_option( $this->prefix.'key', $this->key );
		}
	}

	function deleteKey () {
		if(check_admin_referer('ssi-delete-key')) {
			delete_option( $this->prefix.'key' );
			$this->key = null;
		}
	}

	function buildUrlQuery ( $tipo, $azione, $risorsa = '' ) {
		if(!$this->key)
			return false;
		$queryString = array(
			'key'  => $this->key,
			'data' => base64_encode(json_encode(array(
				'wp'  => get_bloginfo('version'),
				'url' => get_bloginfo('url')
			)))
		);
		$path = array($tipo, $azione);
		if($risorsa)
			array_push($path, $risorsa);
		return $this->apiUrl . implode('/', $path) . '?' . build_query($queryString);
	}

	/**
	 * Filtra i plugin disponibili e restituisce quelli
	 * installati nella forma ['slug' => 'path to file']
	 * 
	 * @param array $plugins 
	 * @return array
	 */
	function pluginsInstalled ( $plugins ) {
		$installed   = array();
		if ( ! function_exists( 'get_plugins' ) )
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		$all_plugins = get_plugins();
		$plugins = (array) $plugins;
		if(!empty($plugins))
			foreach ($all_plugins as $file => $dati)
				foreach ($plugins as $slug => $value)
					if(in_array($slug, array(dirname($file), basename($file, '.php'))))
						$installed[$slug] = $file;
		return $installed;
	}

	function check4update () {
		new \PluginUpdateChecker_2_0 (
			$this->apiUrl . 'sputnik-installer.json',
			__FILE__,
			'sputnik-installer'
		);
	}

	////////////////////////////////////////////////////////////////////////////
	// QUI SOTTO I METODI NECESSARI AL SINGLETON //// http://goo.gl/ECFHsp /////
	////////////////////////////////////////////////////////////////////////////

	/**
	* Returns the *Singleton* instance of this class.
	* @staticvar Singleton $instance The *Singleton* instances of this class.
	* @return Singleton The *Singleton* instance.
	*/
	public static function getInstance()
	{
		static $instance = null;
		if (null === $instance) {
			$instance = new static();
			$instance->init();
		}
		return $instance;
	}

	/**
	* Protected constructor to prevent creating a new instance of the
	* *Singleton* via the `new` operator from outside of this class.
	*/
	protected function __construct()	{}

	/**
	* Private clone method to prevent cloning of the instance of the
	* *Singleton* instance.
	* @return void
	*/
	private function __clone() {}

	/**
	* Private unserialize method to prevent unserializing of the *Singleton*
	* instance.
	* @return void
	*/
	private function __wakeup() {}

} // Sputnik_Installer

// Inizializza il plugin
Sputnik_Installer::getInstance();

///////////////////////////////////////////////////////////////////////////////
/// CRON JOBS /////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

// On activation, set a time, frequency and name of an action hook to be scheduled.
register_activation_hook( __FILE__, 'SSI\\attiva_cron_giornaliero' );
function attiva_cron_giornaliero() {
	wp_schedule_event( time(), 'daily', 'cron_giornaliero_hook' );
}

// On deactivation, remove all functions from the scheduled action hook.
register_deactivation_hook( __FILE__, 'SSI\\disattiva_cron_giornaliero' );
function disattiva_cron_giornaliero() {
	wp_clear_scheduled_hook( 'cron_giornaliero_hook' );
}
