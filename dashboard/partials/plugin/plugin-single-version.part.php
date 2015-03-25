<?php if( $esiste ): ?>

	Versione <?= $version ?>

<?php else: ?>

	Versione <?= $p->version ?> disponibile

<?php endif; ?>


<?php if(version_compare($p->version, $p->maxVersion, '<')): ?>

	<p><strong>Versione <?= $p->maxVersion ?> disponibile con una ulteriore licenza</strong></p>

<?php endif; ?>
