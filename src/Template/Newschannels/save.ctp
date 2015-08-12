<!-- src/Template/Newschannels/save.ctp -->
	<script>
		function goBack() {
    		window.history.back();
		}
	</script>
	
	<div class="md-10">
		<h1>Nachrichtenkanal <?= h( $actionname ); ?></h1>
		<p class="alert <?= h( $alertstyle ); ?>" role="alert">
			<?= h( $responseMessage ); ?>
		</p>
		<p>
			Klicke <?= $this->Html->link( 'hier', '/newschannels/'.$linkback ); ?>, um zur vorherigen Seite zurückzukehren.
		</p>
	</div>