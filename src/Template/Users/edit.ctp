<div class="page-header">
	<h1>Daten der Benutzer_in <?= h( $userData["name"] ); ?> bearbeiten</h1>
</div>
<div class="form-horizontal">
	<?php
	echo $this->Form->create( 'edit_mwuser', [ 'action' => 'edit/' . $userData["name"] ] ) .
		
		// set userright edit token
		$this->Form->hidden( 'urtoken', [ 'value' => $userData["urtoken"] ] ) .
	
	// username field
		'<div class="form-group">' .
			'<div class="col-md-2 col-sm-3" align="right">' .
				$this->Form->label( 'username', 'Benutzer_inname:', [ 'id' => 'label-username', 'class' => 'control-label' ] ) .
			'</div>' .
			'<div class="col-md-10 col-sm-9">' .
				$this->Form->input( 'username', [ 'label' => false, 'value' => $userData["name"], 'class' => 'form-control', 'placeholder' => 'Der Benutzername', 'readonly' ] ) .
			'</div>' .
		'</div>' .
	
	// usergroup fields
		'<div class="form-group">' .
			'<div class="col-md-2 col-sm-3" align="right">' .
				$this->Form->label( 'groups', 'Benutzergruppen:', [ 'id' => 'label-groups', 'class' => 'control-label' ] ) .
			'</div>' .
			'<div class="col-md-10 col-sm-9">';
				//$this->Form->input( 'groups', [ 'label' => false, 'value' => $userData["groups"], 'class' => 'form-control', 'placeholder' => 'Liste der Benutzergruppen des Benutzers' ] ) .
				//$this->Form->select( 'groups', $options, [ 'multiple' => 'checkbox',  ] ) .
				foreach( $userData["groups"] as $groups ) {
					echo '<p>' . $this->Form->checkbox( 'group[' . $groups["name"] . ']', [ 'checked' => $groups["checked"] ] ) . ' ' . $groups["label"] . '</p>';
				}
		echo '</div>' .
		'</div>' .
	
	// submit button
		'<div class="form-group">' .
			'<div class="col-md-2 col-sm-3"></div>' .
			'<div class="col-md-10 col-sm-9">' .
				$this->Form->button( 'Speichern', [ 'class' => 'btn btn-default' ] ) .
			'</div>' .
		'</div>' .
	
	$this->Form->end();
	?>
</div>