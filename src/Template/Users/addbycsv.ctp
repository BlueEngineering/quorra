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
?>
		<table class="table table-striped">
			<tr>
				<th>Name</th>
				<th>Systemmeldung</th>
			</tr>
			<?php
			foreach( $notice as $user ) {
				echo '<tr class="'. $user["cssResult"] .'">' .
						'<td>' .
							$user["name"] .
						'</td>' .
						'<td>' .
						$user["result"] .
						'</td>' .
					'</tr>';
			}
			?>
		</table>
	<?php
	} else {
	?>
		<div class="alert alert-danger">
			<p>Beim Dateiupload ist folgender Fehler aufgetreten:</p>
			<p>-->
			<?php
			echo $error;
			?>
			</p>
			<p>
			<?php
			echo $this->Html->link(
				'Zurück zum Formular',
				[
					'controller'	=> 'users',
					'action'		=> 'createbycsv',
					'_full'			=> true
				]
			);
			?>
			</p>
			</div>
	<?php
	}
} else {
	?>
	<div class="form-horizontal">
		<?php
		echo $this->Form->create(
				'csvfile',
				[
					'type'		=> 'file',
					'enctype'	=> 'multipart/form-data'
				]
			);
			
		echo $this->Form->hidden(
				'urtoken',
				[
					'value'		=> $urtoken
				]
			);
		?>
			<div class="form-group">
				<div class="col-md-2 col-sm-3" align="right">
					<?php
					$this->Form->label(
						'csvfile',
						'CSV Datei wählen:',
						[
							'id'		=> 'label-csvfile',
							'class'		=> 'control-label'
						]
					);
					?>
				</div>
				<div class="col-md-10 col-sm-9">
					<?php
					echo $this->Form->file(
						'csvfile',
						[
							'label'		=> false
						]
					);
					?>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-md-2 col-sm-3" align="right">
					<?php
					echo $this->Form->label(
						'semname',
						'Semesterbezeichnung:',
						[
							'id'		=> 'label-semname',
							'class'		=> 'control-label'
						]
					);
					?>
				</div>
				<div class="col-md-10 col-sm-9">
					<?php
					echo $this->Form->input(
						'semname',
						[
							'label'			=> false,
							'class'			=> 'form-control',
							'placeholder'	=> 'Bezeichnung für das Semester eingeben. Bspw: 2005_2'
						]
					);
					?>
				</div>
			</div>
		
		<!--div class="form-group">
			<div class="col-md-2 col-sm-3" align="right">
				<?php
				echo $this->Form->label(
					'groups',
					'mediaWiki Benutzergruppen (optional):',
					[
						'id'		=> 'label-groups',
						'class'		=> 'control-label'
					]
				);
				?>
			</div>
			<div class="col-md-10 col-sm-9">
				<?php
				echo $this->Form->input(
					'groups',
					[
						'label'			=> false,
						'class'			=> 'form-control',
						'placeholder'	=> 'Trage hier die Namen der mediaWiki Benutzergruppen ein denen die/der Benutzer_in angehören soll.'
					]
				);
				?>
			</div>
			<div class="col-md-2 col-sm-3"></div>
			<div class="col-md-10 col-sm-9 help-block">
				<p>'Wichtig! Die Namen werden durch Kommata und ohne Leerzeichen getrennt. Bsp: bot,bureaucrat,...</p>
			</div>
		</div-->
		
		<div class="form-group">
			<div class="col-md-2 col-sm-3">
			</div>
			<div class="col-md-9 col-sm-9">
				<?php
				echo $this->Form->button(
					'Vorgang starten',
					[
						'class'		=> 'btn btn-default'
					]
				);
				?>
			</div>
		</div>
		
		<?php
		echo $this->Form->end();
		?>
	</div>
<?php
}
?>