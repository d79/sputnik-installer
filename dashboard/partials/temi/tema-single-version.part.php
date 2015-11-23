<?php if( $esiste ): ?>

	Versione <?php echo $tema->get('Version'); ?>

<?php else: ?>

	Versione <?php echo $t->version; ?> disponibile

<?php endif; ?>


<?php if(version_compare($t->version, $t->maxVersion, '<')): ?>

	<p><strong>Versione <?php echo $t->maxVersion; ?> disponibile con una differente licenza</strong></p>

<?php endif; ?>
