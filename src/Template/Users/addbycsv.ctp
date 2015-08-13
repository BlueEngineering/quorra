<div class="page-header">
	<h1>Massenerzeugung mediaWiki Accounts über eine CSV-Datei</h1>
</div>
<div>
<p>
Der innere Aufbau der CSV-Daten muss dem Muster <em>Vorname,Nachname,E-Mail</em> entsprechen.
</p>
<p>
<strong>Beispiel für zwei Nutzer_innen:</strong><br />
<em>Maria,Musterfrau,mariamusterfrau@mustermail.de</em><br />
<em>Max,Mustermann,max.mustermann@mustermail.de</em>
</p>
<hr />
</div>
<?php
// has form a result
if( !empty( $notice ) || !empty( $error ) ) {
	// is given results or an error?
	if( empty( $error ) ) {
		echo '<table class="table table-striped">' .
			'<tr>' .
				'<th>Name</th>' .
				'<th>Systemmeldung</th>' .
			'</tr>';
			
			foreach( $notice as $user ) {
				echo '<tr class="'. $user["cssResult"] .'"><td>' . $user["name"] . '</td><td>' . $user["result"] . '</td></tr>';
			}
		echo '</table>';
	} else {
		echo '<div class="alert alert-danger">' .
			'<p>Beim Dateiupload ist folgender Fehler aufgetreten:</p>' .
			'<p>--> ' . $error . '</p>'.
			'<p>' . $this->Html->link( 'Zurück zum Formular', [ 'controller' => 'users', 'action' => 'createbycsv', '_full' => true ] ) . '</p>'.
			'</div>';
	}
} else {
	// upload form
	echo '<div class="form-horizontal">' .
		// create form
			$this->Form->create( 'csvfile', [ 'type' => 'file', 'enctype' => 'multipart/form-data' ] ) .
			
			$this->Form->hidden( 'urtoken', [ 'value' => $urtoken ] ) .
		
			// file upload
			'<div class="form-group">' .
				'<div class="col-md-2 col-sm-3" align="right">' .
					$this->Form->label( 'csvfile', 'CSV Datei wählen:', [ 'id' => 'label-csvfile', 'class' => 'control-label' ] ) .
				'</div>' .
				'<div class="col-md-10 col-sm-9">' .
					$this->Form->file( 'csvfile', [ 'label' => false ] ) .
				'</div>' .
			'</div>' .
			
			// Semesterbezeichnung angeben (optional)
			'<div class="form-group">' .
				'<div class="col-md-2 col-sm-3" align="right">' .
					$this->Form->label( 'semname', 'Semesterbezeichnung:', [ 'id' => 'label-semname', 'class' => 'control-label' ] ) .
				'</div>' .
				'<div class="col-md-10 col-sm-9">' .
					$this->Form->input( 'semname', [ 'label' => false, 'class' => 'form-control', 'placeholder' => 'Bezeichnung für das Semester eingeben. Bspw: 2005_2' ] ) .
				'</div>' .
			'</div>' .
		
		// usergroups (optional)
		'<!--div class="form-group">' .
			'<div class="col-md-2 col-sm-3" align="right">' .
				$this->Form->label( 'groups', 'mediaWiki Benutzergruppen (optional):', [ 'id' => 'label-groups', 'class' => 'control-label' ] ) .
			'</div>' .
			'<div class="col-md-10 col-sm-9">' .
				$this->Form->input( 'groups', [ 'label' => false, 'class' => 'form-control', 'placeholder' => 'Trage hier die Namen der mediaWiki Benutzergruppen ein denen die/der Benutzer_in angehören soll.' ] ) .
			'</div>' .
			'<div class="col-md-2 col-sm-3"></div>' .
			'<div class="col-md-10 col-sm-9 help-block">' .
				'Wichtig! Die Namen werden durch Kommata und ohne Leerzeichen getrennt. Bsp: bot,bureaucrat,...' .
			'</div>' .
		'</div-->' .
		
			// submit button
			'<div class="form-group">' .
				'<div class="col-md-2 col-sm-3">' .
				'</div>' .
				'<div class="col-md-9 col-sm-9">' .
					$this->Form->button( 'Vorgang starten', [ 'class' => 'btn btn-default' ] ) .
				'</div>' .
			'</div>' .
		
		// close form
		$this->Form->end() .
	'</div>';
}
?>