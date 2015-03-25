<div id="poststuff">
	<div class="postbox">
		<h3 class="hndle"><span>API Key</span></h3>
		<div class="inside">
			<?php if($key): ?>
				<p><strong>Chiave API utilizzata:</strong></p>
				<form action="<?php menu_page_url( 'sputnik-installer' ) ?>" method="post">
					<?php wp_nonce_field( 'ssi-delete-key' ); ?>
					<p>
						<code style="font-size:1.1rem;font-family=monospace"><?= $key ?></code>
						<?php submit_button( 'Rimuovi chiave', 'delete', 'do-ssi-delete-key', false ); ?>
					</p>
				</form>
			<?php else: ?>
				<p><strong>Inserisci la chiave API:</strong></p>
				<form action="<?php menu_page_url( 'sputnik-installer' ) ?>" method="post">
					<?php wp_nonce_field( 'ssi-save-key' ); ?>
					<p>
						<input type="text" name="key" class="regular-text">
						<?php submit_button( 'Usa questa chiave', 'secondary', 'do-ssi-save-key', false ); ?>
					</p>
				</form>
			<?php endif; ?>
		</div>
	</div>
</div>
