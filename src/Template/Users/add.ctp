<div class="page-header">
	<h1>mediaWiki Accounts anlegen</h1>
</div>
<?php
// has form a result
if( !empty( $notice ) && !empty( $cssInfobox ) ) {
	echo '<div class="alert alert-' . $cssInfobox . '">' .
		'<p>' . $notice . '</p>'.
		'<p>' . $this->Html->link( 'Zurück', [ 'controller' => 'users', 'action' => 'add' ] ) . '</p>' .
		'</div>';
} else {

// upload form
echo '<div class="form-horizontal">' .
	// create form
		$this->Form->create( 'create_mw' ) .
		
		// userrights token
		$this->Form->hidden( 'urtoken', [ 'value' => $urtoken ] ) .
	
		// username
		'<div class="form-group">' .
			'<div class="col-md-2 col-sm-3" align="right">' .
				$this->Form->label( 'username', 'Benutzernamen angeben:', [ 'id' => 'label-username', 'class' => 'control-label' ] ) .
			'</div>' .
			'<div class="col-md-10 col-sm-9">' .
				$this->Form->input( 'username', [ 'label' => false, 'class' => 'form-control', 'placeholder' => 'Gewünschter Benutzernamen' ] ) .
			'</div>' .
			'<div class="col-md-2 col-sm-3"></div>' .
			'<div class="col-md-10 col-sm-9 help-block">' .
				'Es wird zwischen Groß- und Kleinschreibung unterschieden!' .
			'</div>' .
		'</div>' .
		
		// real name (optional)
		'<div class="form-group">' .
			'<div class="col-md-2 col-sm-3" align="right">' .
				$this->Form->label( 'realname', 'Bürgerliche Name (optional):', [ 'id' => 'label-realname', 'class' => 'control-label' ] ) .
			'</div>' .
			'<div class="col-md-10 col-sm-9">' .
				$this->Form->input( 'realname', [ 'label' => false, 'class' => 'form-control', 'placeholder' => 'Der bürgerliche Name der/des Benutzer_in.' ] ) .
			'</div>' .
		'</div>' .
		
		// E-Mail address
		'<div class="form-group">' .
			'<div class="col-md-2 col-sm-3" align="right">' .
				$this->Form->label( 'email', 'E-Mail Adresse:', [ 'id' => 'label-email', 'class' => 'control-label' ] ) .
			'</div>' .
			'<div class="col-md-10 col-sm-9">' .
				$this->Form->input( 'email', [ 'label' => false, 'class' => 'form-control', 'placeholder' => 'E-Mail Adresse.' ] ) .
			'</div>' .
			'<div class="col-md-2 col-sm-3"></div>' .
			'<div class="col-md-10 col-sm-9 help-block">' .
				'Die E-Mail Adresse wird benötigt um das zufällig generierte Passwort zusenden zu können.' .
			'</div>' .
		'</div>' .
		
		// usergroups (optional)
	//
		'<div class="form-group">' .
			'<div class="col-md-2 col-sm-3" align="right">' .
				$this->Form->label( 'groups', 'Benutzergruppen:', [ 'id' => 'label-groups', 'class' => 'control-label' ] ) .
			'</div>' .
			'<div class="col-md-10 col-sm-9">';
				foreach( $ugroups as $groups ) {
					if( $groups["name"] != 'user' ) {
						echo '<p>' . $this->Form->checkbox( 'group[' . $groups["name"] . ']' ) . ' ' . $groups["label"] . '</p>';
					}
				}
		echo '</div>' .
		'</div>' .
	
		// submit button
		'<div class="form-group">' .
			'<div class="col-md-2 col-sm-3">' .
			'</div>' .
			'<div class="col-md-9 col-sm-9">' .
				$this->Form->button( 'Account anlegen', [ 'class' => 'btn btn-default' ] ) .
			'</div>' .
		'</div>' .
	
	// close form
	$this->Form->end() .
'</div>';
}
?>