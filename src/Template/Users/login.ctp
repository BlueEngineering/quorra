<div class="page-header">
	<h1>Welcome on Blue Engineering Quorra</h1>
</div>
<?php
if( !empty( $errorMessage ) && !empty( $cssInfobox ) ) {
	echo '<div class="alert alert-' . $cssInfobox . '"><p>Ein Login war nicht möglich. Bitte überprüfe deinen Benutzernamen und Passwort</p></div>';
}
?>
<div class="form-horizontal">
	<?php
	// create a html form
	echo $this->Form->create( 'User', [ 'type' => 'post', 'action' => 'login' ] );
			
   	echo '<fieldset>' .
		'<legend>' . __('Please enter your username and password') . '</legend>' .
		'<div class="form-group">' .
			'<div class="col-md-2 col-sm-3" align="right">' .
				$this->Form->label( 'username', 'Your username:', [ 'id' => 'label-username', 'class' => 'control-label' ] ) .
			'</div>' .
			'<div class="col-md-10 col-sm-9">' .
				$this->Form->input( 'username', [ 'label' => false, 'class' => 'form-control', 'placeholder' => 'Your username please' ] ) .
			'</div>' .
		'</div>' .
		'<div class="form-group">' .
			'<div class="col-md-2 col-sm-3" align="right">' .
				$this->Form->label( 'password', 'Your password:', [ 'id' => 'label-password', 'class' => 'control-label' ] ) .
			'</div>' .
			'<div class="col-md-10 col-sm-9">' .
				$this->Form->input( 'password', [ 'label' => false, 'class' => 'form-control', 'placeholder' => 'Your password please' ] ) .
			'</div>' .
		'</div>' .
		'</fieldset>'.
		'<div class="form-group">' .
			'<div class="col-md-2 col-sm-3">' .
			'</div>' .
			'<div class="col-md-9 col-sm-9">' .
			$this->Form->button( 'Login', [ 'type' => 'submit', 'class' => 'btn btn-default' ] ) .
			'</div>' .
		'</div>';
	
	// close created html form
	echo $this->Form->end();
	?>
</div>