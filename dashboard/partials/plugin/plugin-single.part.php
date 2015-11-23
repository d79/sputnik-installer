<?php
$class = array($esiste ? 'active' : 'inactive');
if($update) $class[] = 'update';
?>
<tr class="<?php echo implode(' ', $class); ?>">
	<th scope="row" class="check-column"></th>
	<td class="plugin-title">

		<strong><?php echo $p->name; ?></strong>

		<?php if(isset($p->details_url)): ?>
		<div class="row-actions visible">
			<a href="<?php echo $p->details_url; ?>?TB_iframe=true&amp;width=800&amp;height=550" title="<?php echo $p->name; ?>" class="edit thickbox">Dettagli plugin</a>
		</div>
		<?php endif; ?>

	</td>
	<td class="col-version">

		<?php include plugin_dir_path( __FILE__ ).'plugin-single-version.part.php'; ?>

	</td>
	<td class="col-stato">
		
		<?php include plugin_dir_path( __FILE__ ).'plugin-single-stato.part.php'; ?>

	</td>
	<td class="col-azioni">

		<?php include plugin_dir_path( __FILE__ ).'plugin-single-azioni.part.php'; ?>

	</td>
</tr>