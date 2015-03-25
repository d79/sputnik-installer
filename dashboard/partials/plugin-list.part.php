<h3> Plugin </h3>

<?php if(is_object($plugins) && !empty($plugins)): ?>

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
			foreach($plugins as $slug => $p) {
				if($esiste = isset($installed[$slug]))
					$plugin = get_plugin_data( $plugins_path . $installed[$slug] );
				$version = $esiste ? $plugin['Version'] : '';
				$update = $esiste && version_compare($version, $p->version, '<');
				include plugin_dir_path( __FILE__ ).'plugin/plugin-single.part.php';
				if($update)
					include plugin_dir_path( __FILE__ ).'plugin/plugin-tr-update.part.php';
			}
			?>
		</tbody>
	</table>

<?php else: ?>

	<p><em>Nessun plugin disponibile</em></p>

<?php endif; ?>