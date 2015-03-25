<?php if( $esiste ): ?>

	Versione <?= $tema->get('Version') ?>

<?php else: ?>

	Versione <?= $t->version ?> disponibile

<?php endif; ?>


<?php if(version_compare($t->version, $t->maxVersion, '<')): ?>

	<p><strong>Versione <?= $t->maxVersion ?> disponibile con una ulteriore licenza</strong></p>

<?php endif; ?>
