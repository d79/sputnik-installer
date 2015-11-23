<?php if ( $esiste ): ?>

	<?php if( $update ): ?>

		<a class="button button-primary" href="<?php echo wp_nonce_url( admin_url('update.php?action=upgrade-plugin&plugin='.$installed[$slug]), 'upgrade-plugin_'.$installed[$slug] ); ?>">Aggiorna plugin</a>

	<?php else: ?>

		<span class="dashicons dashicons-yes"></span>

	<?php endif; ?>

<?php else: // installa plugin ?>

	<form action="<?php menu_page_url( 'sputnik-installer' ) ?>" method="post">
		<?php wp_nonce_field( 'ssi-plugin-install' ); ?>
		<input type="hidden" name="plugin" value="<?php echo $slug; ?>">
		<?php submit_button( 'Installa plugin', 'primary', 'do-ssi-plugin-install' ); ?>
	</form>

<?php endif; ?>