<?php
$class = array($tema->exists() ? 'active' : 'inactive');
if($update) $class[] = 'update';
?>
<tr class="<?= implode(' ', $class) ?>">
	<th scope="row" class="check-column"></th>
	<td class="plugin-title">

		<strong><?= $t->name ?></strong>

		<?php if(isset($t->details_url)): ?>
		<div class="row-actions visible">
			<a href="<?= $t->details_url ?>?TB_iframe=true&amp;width=800&amp;height=550" title="<?= $t->name ?>" class="edit thickbox">Dettagli tema</a>
		</div>
		<?php endif; ?>

	</td>
	<td class="col-version">

		<?php include plugin_dir_path( __FILE__ ).'tema-single-version.part.php'; ?>

	</td>
	<td class="col-stato">
		
		<?php include plugin_dir_path( __FILE__ ).'tema-single-stato.part.php'; ?>

	</td>
	<td class="col-azioni">

		<?php include plugin_dir_path( __FILE__ ).'tema-single-azioni.part.php'; ?>

	</td>
</tr>
