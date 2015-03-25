<?php if ( $esiste ): ?>

	<?php if( $update ): ?>

		<a class="button button-primary" href="<?= wp_nonce_url( admin_url('update.php?action=upgrade-theme&theme='.$slug), 'upgrade-theme_'.$slug ); ?>">Aggiorna tema</a>

	<?php else: ?>

		<span class="dashicons dashicons-yes"></span>

	<?php endif; ?>

<?php else: // installa tema ?>

	<form action="<?php menu_page_url( 'sputnik-installer' ) ?>" method="post">
		<?php wp_nonce_field( 'ssi-theme-install' ); ?>
		<input type="hidden" name="tema" value="<?= $slug ?>">
		<?php submit_button( 'Installa tema', 'primary', 'do-ssi-theme-install' ); ?>
	</form>

<?php endif; ?>