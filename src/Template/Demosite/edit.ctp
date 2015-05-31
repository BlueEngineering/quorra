<div class="md-10">
	<p>
	<div class="alert <?= h($alertstyle); ?>" role="alert">
	<?= h($result); ?>
	</div>
	</p>
	<p>
	Klicke <?= $this->Html->link( 'hier', '/demosite' ); ?>, um zum Editor zurückzukehren.
	</p>	
</div>