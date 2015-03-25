<?php
namespace SSI\Plugin;

function SSI() { return \SSI\Sputnik_Installer::getInstance(); }

function lista_plugin ()
{
	if($url = SSI()->buildUrlQuery('plugin', 'list')) {
		$plugin = SSI()->fetch_json($url);
		update_option( SSI()->prefix.'plugin', $plugin );
		return $plugin;
	}
	return false;
} // lista_temi

function installa_plugin ()
{
	$slug = isset($_POST['plugin']) ? $_POST['plugin'] : '';

	if ( !$slug )
		wp_die( 'Errore: plugin non comunicato.' );

	$url = SSI()->buildUrlQuery('plugin', 'install', $slug);
	if($url === false)
		wp_die('Errore: Chiave API non impostata');

	include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	include_once plugin_dir_path( __FILE__ ).'class-file-download-upgrader.php';

	if ( ! current_user_can( 'upload_plugins' ) ) {
		wp_die( __( 'You do not have sufficient permissions to install plugins on this site.' ) );
	}

	check_admin_referer('ssi-plugin-install');

	// download file to temp dir
	$timeout   = 5; // sec
	$temp_file = download_url( $url, $timeout );

	if (!is_wp_error( $temp_file )) {

		// array based on $_FILE as seen in PHP file uploads
		$file = array(
			'name' => $slug.'.zip',
			'type' => 'application/zip',
			'tmp_name' => $temp_file,
			'error' => 0,
			'size' => filesize($temp_file),
		);

		$file_upload = new \SSI\File_Download_Upgrader( $file );

		$title = __('Upload Plugin');
		$parent_file = 'plugins.php';
		$submenu_file = 'plugin-install.php';
		require_once(ABSPATH . 'wp-admin/admin-header.php');

		$title = sprintf( __('Installing Plugin from uploaded file: %s'), esc_html( basename( $file_upload->filename ) ) );
		$nonce = 'plugin-upload';
		$url = add_query_arg(array('package' => $file_upload->id), 'update.php?action=upload-plugin');
		$type = 'upload'; //Install plugin type, From Web or an Upload.

		$upgrader = new \Plugin_Upgrader( new \Plugin_Installer_Skin( compact('type', 'title', 'nonce', 'url') ) );
		$result = $upgrader->install( $file_upload->package );

		if ( $result || is_wp_error($result) )
			$file_upload->cleanup();

		printf('<p><a href="%s">Ritorna a Sputnik Installer</a></p>', menu_page_url( 'sputnik-installer', false ));

		include(ABSPATH . 'wp-admin/admin-footer.php');

	} else wp_die( $temp_file->get_error_message() );

}

function set_update_checker() {

	$plugins = get_option( SSI()->prefix.'plugin', false );

	if(!$plugins) return;

	$plugins = (array) $plugins;
	$installed = SSI()->pluginsInstalled( $plugins );
	if(!empty($installed))
		foreach($installed as $slug => $file)
			new \PluginUpdateChecker_2_0(SSI()->buildUrlQuery('plugin', 'list', $slug), SSI()->pluginsDir.$file, $slug);

} // set_update_checker