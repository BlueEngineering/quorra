<div class="page-header">
	<h1>Seminar anlegen</h1>
</div>

<?php
// is a notice from function set?
if( !empty( $notice ) ) {
	echo '<div class="col-md-12 col-sm-12 alert alert-' . $cssInfobox . '">' .
		'<p>' . $notice . '</p>' .
		'<p>' .
		$this->Html->link( 'Zurück zur Übersicht', [ 'controller' => 'seminars', 'action' => 'index', '_full' => true ] ) .
		'</p>' .
		'</div>';
	return;
}
?>

<div class="form-horizontal">
	<?php echo $this->Form->create( 'addSeminar' ); ?>
	
	<div class="form-group">
		<div class="col-md-3 col-sm-4" align="right">
			<?php
			echo $this->Form->label( 'semname', 'Seminarbezeichnung:', [ 'id' => 'label-semname', 'class' => 'control-label' ] );
			?>
		</div>
		<div class="col-md-9 col-sm-8">
			<?php
			echo $this->Form->input( 'semname', [ 'label' => false, 'class' => 'form-control', 'placeholder' => 'Den Namen oder die Bezeichnung des Seminars.' ] );
			?>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-md-3 col-sm-4" align="right">
			<?php
			echo $this->Form->label( 'semacronym', 'Kürzel des Seminars:', [ 'id' => 'label-semacronym', 'class' => 'control-label' ] );
			?>
		</div>
		<div class="col-md-9 col-sm-8">
			<?php
			echo $this->Form->input( 'semacronym', [ 'label' => false, 'class' => 'form-control', 'placeholder' => 'Kürzel des Seminars. Bspw. "SemBer" für Seminar in Berlin.' ] );
			?>
		</div>
		<div class="col-md-3 col-sm-4"></div>
		<div class="col-md-9 col-sm-8 help-block">
			<p><strong>Hinweis:</strong> Dieses Feld wird für die Benennung des entsprechenden Arbeitsbereiches (Namensraum, namespace)
			im mediaWiki genutzt. Die Bezeichnung muss zusammenhängend, also frei von Leerzeichen, sein.</p>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-md-3 col-sm-4" align="right">
			<?php
				echo $this->Form->label( 'tutgrp', 'Benutzergruppenname für die Tutor_innen:', [ 'id' => 'label-tutgrp', 'class' => 'control-label' ] );
			?>
		</div>
		<div class="col-md-9 col-sm-8">
			<?php
				echo $this->Form->input( 'tutgrp', [ 'label' => false, 'class' => 'form-control', 'placeholder' => 'Name für die Benutzergruppe der TutorInnen angeben. Bspw. SemBerTut.' ] );
			?>
		</div>
		<div class="col-md-3 col-sm-4"></div>
		<div class="col-md-9 col-sm-8 help-block">
			<p><strong>Hinweis:</strong> Die Bezeichnung muss zusammenhängend, also frei von Leerzeichen, sein.</p>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-md-3 col-sm-4" align="right">
			<?php
				echo $this->Form->label( 'memgrp', 'Benutzergruppenname für die Teilnehmer_innen:', [ 'id' => 'label-memgrp', 'class' => 'control-label' ] );
			?>
		</div>
		<div class="col-md-9 col-sm-8">
			<?php
				echo $this->Form->input( 'memgrp', [ 'label' => false, 'class' => 'form-control', 'placeholder' => 'Name für die Benutzergruppe der Teilnehmer_innen angeben. Bspw. SemBerMem' ] );
			?>
		</div>
		<div class="col-md-3 col-sm-4"></div>
		<div class="col-md-9 col-sm-8 help-block">
			<p><strong>Hinweis:</strong> Die Bezeichnung muss zusammenhängend, also frei von Leerzeichen, sein.</p>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-md-3 col-sm-4" align="right">
			<?php
			echo $this->Form->label( 'closedArea', 'Nichtöffentlicher Arbeitsbereich?', [ 'id' => 'label-closedArea', 'class' => 'control-label' ] );
			?>
		</div>
		<div class="col-md-9 col-sm-8 ">
			<?php
				echo $this->Form->radio( 'closedArea', [
					[ 'value' => '1', 'text' => 'ja ', 'checked' => true ],
					[ 'value' => '0', 'text' => 'nein' ]
				], [ 'class' => 'radio-inline' ] );
			?>
		</div>
		<div class="col-md-3 col-sm-4"></div>
		<div class="col-md-9 col-sm-8 help-block">
			<p>
				Mit dieser Einstellung kann festgelegt werden, ob die Seiten in diesem Bereich auch von Nichtteilnehmer_innen eingesehen
				werden können. Ist der Zugriff nur für Teilnehmer_innen erlaubt, müssen die zu veröffentlichenden Seiten manuell
				durch Verschieben (nur Tutor_innen) freigegeben werden.
			</p>
		</div>
	</div>
	
	<div>
		<hr />
	</div>
	
	<div class="form-group">
		<div class="col-md-3 col-sm-4" align="right">
			<?php
				echo $this->Form->label( 'mailfrom', 'Absender E-Mail Adresse:', [ 'id' => 'label-mailfrom', 'class' => 'control-label' ] );
			?>
		</div>
		<div class="col-md-9 col-sm-8">
			<?php
				echo $this->Form->input( 'mailfrom', [ 'class' => 'form-control', 'label' => false, 'placeholder' => 'Die Absendeadresse die angezeigt werden soll. Bsp: seminar@blue-engineering.org' ] );
			?>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-md-3 col-sm-4" align="right">
			<?php
				echo $this->Form->label( 'mailsub', 'Betreff der E-Mail:', [ 'id' => 'label-mailsub', 'class' => 'control-label' ] );
			?>
		</div>
		<div class="col-md-9 col-sm-8">
			<?php
				echo $this->Form->input( 'mailsub', [ 'class' => 'form-control', 'label' => false, 'placeholder' => 'Betreff der E-Mail. Beispiel: [Blue Engineering] Willkommen im Seminarbereich' ] );
			?>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-md-3 col-sm-4" align="right">
			<?php
				echo $this->Form->label( 'mailtxt', 'E-Mail Begrüßungstext:', [ 'id' => 'label-mailtxt', 'class' => 'control-label' ] );
			?>
		</div>
		<div class="col-md-9 col-sm-8">
			<?php
				$defaulttext = "Hallo %name,\n\nfür dich wurde ein mediaWiki Benutzer_inkonto angelegt. Dieses wird benötigt um auf die Informationsseiten\ndes Seminars zugreifen zu können.\n\nDeine Benutzer_inkontodaten sind:\nDein Benutzer_inkontoname: %username (beachte bitte die Groß- und Kleinschreibung!)\nDein Password: %userpass\n\nDas BE mediaWiki erreichst du über %semurl\n\nUm dein Passwort zu ändern musst du dich erfolgreich einloggen. Anschließend kannst du dieses in den Einstellungen unter %urlwiki/index.php/Spezial:Einstellungen erreichen.\n\nViele Grüße\nDein Blue Engineering Seminarteam";
				echo $this->Form->textarea( 'mailtxt', [ 'class' => 'form-control', 'escape' => true, 'id' => 'mailtxt', 'value' => $defaulttext, 'rows' => 20 ] );
			?>
		</div>
		<div class="col-md-3 col-sm-4"></div>
		<div class="col-md-9 col-sm-8 help-block">
			<?php
				echo $this->element( 'list_controlcommands_email' );
			?>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-md-3 col-sm-4"></div>
		<div class="col-md-9 col-sm-8">
			<?php echo $this->Form->button( 'Anlegen', [ 'class' => 'btn btn-default' ] ); ?>
		</div>
	</div>
	
	<?php echo $this->Form->end(); ?>
</div>