<?php
namespace SSI;

/**
 * Upgrade Skin helper for File downloads.
 * This class handles the download process and passes it 
 * as if it's a local file to the Upgrade/Installer functions.
 * Derived from /wp-admin/includes/class-wp-upgrader.php
 */
class File_Download_Upgrader {
 
	/**
	* The full path to the file package.
	*/
	public $package;

	/**
	* The name of the file.
	*/
	public $filename;

	/**
	* The ID of the attachment post for this file.
	*/
	public $id = 0;
 
	/**
	* Construct the upgrader for a file.
	*/
	public function __construct( $temp_file ) {

		//Handle a newly uploaded file
		if ( is_array($temp_file) && ! empty($temp_file) ) {

			$overrides = array( 'test_form' => false, 'test_type' => false );
			$file = wp_handle_sideload( $temp_file, $overrides );

			if ( !empty( $file['error'] ) )
				wp_die( $file['error'] );

			$this->filename = $temp_file['name'];
			$this->package  = $file['file'];

			// Construct the object array
			$object = array(
				'post_title'     => $this->filename,
				'post_content'   => $file['url'],
				'post_mime_type' => $file['type'],
				'guid'           => $file['url'],
				'context'        => 'upgrader',
				'post_status'    => 'private'
			);

			// Save the data.
			$this->id = wp_insert_attachment( $object, $file['file'] );

			// Schedule a cleanup for 2 hours from now in case of failed install.
			wp_schedule_single_event( time() + 2 * HOUR_IN_SECONDS, 'upgrader_scheduled_cleanup', array( $this->id ) );

		}

	}
 
	/**
	* Delete the attachment/uploaded file.
	*/
	public function cleanup() {
		if ( $this->id )
			wp_delete_attachment( $this->id );
		elseif ( file_exists( $this->package ) )
			return @unlink( $this->package );
		return true;
	}
}