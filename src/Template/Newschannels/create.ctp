<!-- src/Template/Newschannels/edit.ctp -->
	<div class="md-10">
		<h1>Nachrichtenkanal: Detailansicht</h1>
		<p>
			<!-- form starts -->
			<?php
			echo $this->Form->create( null, [ 'url' => [ 'controller' => 'Newschannels', 'action' => 'save' ] ] );
			
			echo $this->Form->hidden( 'channel_id', [ 'value' => '-1' ] );
			
			echo $this->Form->hidden( 'mw_article_id', [ 'value' => '-1' ] );
			
			echo $this->Form->input( 'channel_name', [ 'label' => 'Name des Nachrichtenkanals', 'placeholder' => 'Bitte gebe hier den Namen des Nachrichtenkanals ein.', 'class' => 'form-control' ] );
			
			echo '</p><p>';
			
			//echo $this->Form->reset( 'Zurücksetzen', [ 'type' => 'reset' ] );
			echo $this->Form->submit( 'Speichern', [ 'type' => 'submit', 'class' => 'btn btn-primary' ] );
			
			echo $this->Form->end();
			?>
			<!-- form ends -->
		</p>
	</div>