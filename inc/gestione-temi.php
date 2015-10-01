<?php
namespace SSI\Temi;

function SSI() { return \SSI\Sputnik_Installer::getInstance(); }

function lista_temi ()
{
	if($url = SSI()->buildUrlQuery('temi', 'list')) {
		$temi = SSI()->fetch_json($url);
		update_option( SSI()->prefix.'temi', $temi );
		return $temi;
	}
	return false;
} // lista_temi

function installa_tema ()
{
	$tema_slug = isset($_POST['tema']) ? $_POST['tema'] : '';

	if ( !$tema_slug )
		wp_die( 'Errore: tema non comunicato.' );

	$url = SSI()->buildUrlQuery('temi', 'install', $tema_slug);
	if($url === false)
		wp_die('Errore: Chiave API non impostata');

	include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	include_once plugin_dir_path( __FILE__ ).'class-file-download-upgrader.php';

	if ( ! current_user_can( 'upload_themes' ) )
		wp_die( __( 'You do not have sufficient permissions to install themes on this site.' ) );
	check_admin_referer('ssi-theme-install'); // check nonce

	// download file to temp dir
	$timeout   = 5; // sec
	$temp_file = download_url( $url, $timeout );

	if (!is_wp_error( $temp_file )) {

		// array based on $_FILE as seen in PHP file uploads
		$file = array(
			'name' => $tema_slug.'.zip',
			'type' => 'application/zip',
			'tmp_name' => $temp_file,
			'error' => 0,
			'size' => filesize($temp_file),
		);

		$file_upload = new \SSI\File_Download_Upgrader( $file );

		wp_enqueue_script( 'customize-loader' );
		$title = 'Sputnik Installer - Installazione Tema';
		$parent_file = 'themes.php';
		$submenu_file = 'theme-install.php';
		require_once(ABSPATH . 'wp-admin/admin-header.php');
		$title = sprintf( __('Installing Theme from uploaded file: %s'), esc_html( basename( $file_upload->filename ) ) );
		$nonce = 'ssi-theme-install';
		$url = add_query_arg(array('package' => $file_upload->id), 'update.php?action=upload-theme');
		$type = 'upload'; //Install plugin type, From Web or an Upload.
		$upgrader = new \Theme_Upgrader( new \Theme_Installer_Skin( compact('type', 'title', 'nonce', 'url') ) );
		$result = $upgrader->install( $file_upload->package );
		if ( $result || is_wp_error($result) )
			$file_upload->cleanup();

		printf('<p><a href="%s">Ritorna a Sputnik Installer</a></p>', menu_page_url( 'sputnik-installer', false ));

		include(ABSPATH . 'wp-admin/admin-footer.php');

	} else wp_die( $temp_file->get_error_message() );

	// wp_die('<pre>'.var_export($response, true).'</pre>');

} // installa_tema

function set_update_checker() {
	
	loop_temi( function( $slug ) {
		new ThemeUpdateChecker($slug, SSI()->buildUrlQuery('temi', 'list', $slug));
	} );

} // set_update_checker

function check_now() {

	loop_temi( function( $slug, $wp_theme, $temi ) {
		if(version_compare($wp_theme->get( 'Version' ), $temi[$slug]->version, '<')) {
			$TUC = new ThemeUpdateChecker($slug, SSI()->buildUrlQuery('temi', 'list', $slug));
			$TUC->checkForUpdates();
		}
	} );

} // check_now

function loop_temi( $callback ) {

	$temi = get_option( SSI()->prefix.'temi', false );

	if(!$temi) return;

	$temi = (array) $temi;
	if(!empty($temi))
		foreach($temi as $slug => $tema) {
			$wp_theme = wp_get_theme($slug);
			if(!$wp_theme->exists())
				break;
			$callback( $slug, $wp_theme, $temi );
		}

} // loop_temi