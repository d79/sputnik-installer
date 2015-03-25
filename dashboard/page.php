<div class="wrap">

	<h2> Sputnik Installer </h2>

	<?php include plugin_dir_path( __FILE__ ).'partials/api-key.part.php'; ?>

	<?php if($key): ?>

		<?php add_thickbox(); ?>
		<?php include plugin_dir_path( __FILE__ ).'partials/temi-list.part.php'; ?>
		<?php include plugin_dir_path( __FILE__ ).'partials/plugin-list.part.php'; ?>

	<?php endif; // if $key ?>

</div>
