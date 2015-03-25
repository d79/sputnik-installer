<h3> Temi </h3>

<?php if(is_object($temi) && !empty($temi)): ?>

	<table class="wp-list-table widefat plugins">
		<thead>
			<tr>
				<th scope="col" class="check-column"></th>
				<th scope="col"><span>Nome</span></th>
				<th scope="col"><span>Versione</span></th>
				<th scope="col"><span>Stato</span></th>
				<th scope="col"><span>Azioni</span></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th scope="col" class="check-column"></th>
				<th scope="col"><span>Nome</span></th>
				<th scope="col"><span>Versione</span></th>
				<th scope="col"><span>Stato</span></th>
				<th scope="col"><span>Azioni</span></th>
			</tr>
		</tfoot>
		<tbody id="the-list">
			<?php
			foreach($temi as $slug => $t) {
				$tema   = wp_get_theme( $slug );
				$esiste = $tema->exists();
				$update = $esiste && version_compare($tema->get( 'Version' ), $t->version, '<');
				include plugin_dir_path( __FILE__ ).'temi/tema-single.part.php';
				if($update)
					include plugin_dir_path( __FILE__ ).'temi/tema-tr-update.part.php';
			}
			?>
		</tbody>
	</table>

<?php else: ?>

	<p><em>Nessun tema disponibile</em></p>

<?php endif; ?>