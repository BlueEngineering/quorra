<div class="page-header">
	<h1>Übersicht der im BE mediaWiki bestehenden Arbeitsbereiche</h1>
</div>
<div>
	<table class="table table-striped">
		<tr>
			<th>
			<?php
			echo $this->Paginator->sort( 'name', 'Bezeichnung' );
			?>
			</th>
			<th>Diskussionsbereich?</th>
			<th>Öffentlich zugänglich?</th>
			<th>Tutor_innengruppe</th>
			<th>Teilnehmer_innengruppe</th>
			<th>&nbsp;</th>
		</tr>
		<?php
			foreach( $workspaces as $workspace ) {
				echo '<tr>';
					echo '<td>' . $workspace["name"] . '</td>';
					echo ( $workspace["talk_namespace"] > 0 ) ? '<td>ja</td>' : '<td>nein</td>';
					echo ( $workspace["public"] == 1 ) ? '<td>ja</td>' : '<td>nein</td>';
					echo '<td>' . $workspace["tutgrp"] . '</td>';
					echo ( $workspace["memgrp"] != NULL ) ? '<td>' . $workspace["memgrp"] . '</td>' : '<td>-</td>';
					echo '<td>';
					echo $this->Html->Link(
						$this->Html->tag(
							'button',
							$this->Html->tag(
								'span',
								'',
								[
									'class'		=> 'glyphicon glyphicon-pencil'
								]
							),
							[
								'class'		=> 'btn btn-success'
							]
						),
						[
							'controller'	=> 'workspaces',
							'action'		=> 'edit/' . $workspace["id"]
						],
						[
							'escapeTitle'	=> false
						]
					);
					echo '</td>';
				echo '</tr>';
			}
		?>
	</table>
</div>
<div class="text-center">
	<nav>
		<ul class="pagination">
			<?php
				// prev button
				echo $this->Paginator->prev(
					'&laquo;',
					[
						'escape'		=> false
					]
				);
				
				echo $this->Paginator->numbers();
				
				// next button
				echo $this->Paginator->next(
					'&raquo;',
					[
						'escape'		=> false
					]
				);
			?>
		</ul>
	</nav>
</div>