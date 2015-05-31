<!-- src/Template/Article/index.ctp - main template of article -->
		<script type="text/javascript">
			tinymce.init({
				selector:	"textarea",
				theme:		"modern",
				width:		'100%',
				height:		700,
				plugins:    "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality emoticons template paste textcolor",
				statusbar:  false,
				menubar:    false,
				toolbar:    "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons | code"
			});
		</script>
		
		<div class="md-10">
			<h1>Demosite für Quorra WYSIWYG-Editor</h1>
			<p>
			Willkommen auf der Demoseite des WYSIWYG-Editors der Quorra Suite. Mit dieser Demoseite wollen wir auch jenen Benutzern eine Möglichkeit geben den WYSIWYG-Editor auch ohne einen bestehenden Account im mediaWiki
			zu testen. Der Editor ermöglicht nur das Bearbeiten der <a href="<?= h($url_demosite) ?>" target="_blank">Demosite</a>. Mehr zu Quorra könnt ihr unter <a href="<?= h($quorra_src) ?>" target="_blank"><?= h($quorra_src) ?></a> erfahren.
			</p>
			<p>
			<!-- form starts -->
			<?=
			$this->Html->script('tinymce/tinymce.min.js', [ 'block' => 'script' ] );
			
			echo $this->Form->create( null, [ 'url' => [ 'controller' => 'Demosite', 'action' => 'edit' ] ] );
			
			echo $this->Form->hidden( 'mw_editToken', [ 'value' => $data['editToken'] ] );
			
			echo $this->Form->hidden( 'mw_curTimestamp', [ 'value' => $data['curTimestamp'] ] );
			
			echo $this->Form->hidden( 'mw_articleId', [ 'value' => $data['articleId'] ] );
			
			echo $this->Form->input( 'mw_articleTitle', [ 'label' => 'Artikelname', 'value' => $data['articleTitle'], 'placeholder' => 'Gebe den Titel des Artikels ein', 'class' => 'form-control' ] );
			
			echo '</p><p>';
			
			echo $this->Form->textarea( 'mw_articleContent', [ 'value' => $data['articleText'], 'class' => 'form-control' ] );
			
			//echo $this->Form->reset( 'Zurücksetzen', [ 'type' => 'reset' ] );
			echo $this->Form->submit( 'Speichern', [ 'type' => 'submit', 'class' => 'btn btn-primary' ] );
			
			echo $this->Form->end();
			?>
			<!-- form ends -->
			</p>
		</div>
