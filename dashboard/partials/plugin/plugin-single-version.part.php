<?php if( $esiste ): ?>

	Versione <?php echo $version; ?>

<?php else: ?>

	Versione <?php echo $p->version; ?> disponibile

<?php endif; ?>


<?php if(version_compare($p->version, $p->maxVersion, '<')): ?>

	<p><strong>Versione <?php echo $p->maxVersion; ?> disponibile con una differente licenza</strong></p>

<?php endif; ?>
