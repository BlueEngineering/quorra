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
	echo $this->Form->create( 'User', [ 'type' => 'post', 'action' => 'login', 'class' => 'form-horizontal' ] );
			
   	echo '<fieldset>' . "\n" .
		'<legend>' . __('Please enter your username and password') . '</legend>' . "\n" .
		'<div class="form-group">' . "\n" .
			'<div class="col-md-2 col-sm-3" align="right">' . "\n" .
				$this->Form->label( 'username', 'Your username:', [ 'id' => 'label-username', 'class' => 'control-label' ] ) . "\n" .
			'</div>' . "\n" .
			'<div class="col-md-10 col-sm-9">' . "\n" .
				$this->Form->input( 'username', [ 'label' => false, 'class' => 'form-control', 'placeholder' => 'Your username please' ] ) . "\n" .
			'</div>' . "\n" .
		'</div>' . "\n" .
		'<div class="form-group">' . "\n" .
			'<div class="col-md-2 col-sm-3" align="right">' . "\n" .
				$this->Form->label( 'password', 'Your password:', [ 'id' => 'label-password', 'class' => 'control-label' ] ) . "\n" .
			'</div>' . "\n" .
			'<div class="col-md-10 col-sm-9">' . "\n" .
				$this->Form->input( 'password', [ 'label' => false, 'class' => 'form-control', 'placeholder' => 'Your password please' ] ) . "\n" .
			'</div>' . "\n" .
		'</div>' . "\n" .
		'</fieldset>'. "\n" .
		'<div class="form-group">' . "\n" .
			'<div class="col-md-2 col-sm-3">' . "\n" .
			'</div>' . "\n" .
			'<div class="col-md-9 col-sm-9">' . "\n" .
			$this->Form->submit(__( 'Login', [ 'class' => 'btn btn-default' ] )) . "\n" .
			'</div>' .
		'</div>';
	
	// close created html form
	echo $this->Form->end();
	?>
</div>