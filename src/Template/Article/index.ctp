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
				toolbar:    "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons"
			});
		</script>
		
		<div class="md-10">
			<h1>Demoartikel im BE mediaWiki über den WYSIWYG-Editor bearbeiten</h1>
			<p>
			Bei diesem WYSIWYG-Editor <a href="#glossar1">[1]</a> hanelt es sich um einen Prototypen. Dieser dient dazu die Kommunikation mit der mediaWiki Software zu testen. Bei der Kommunikation geht es vor allem um das
			abrufen, in den Editor laden und speichern des mediaWiki Inhalts. Ebenso wird der mediaWiki-Syntax-Parser <a href="#glossar2">[2]</a> von Quorra getestet.
			</p>
			<p>
			Es kann vorläufig nur der Inhalt des Artikels <a href="http://blue-eng.km.tu-berlin.de/index.php/Demo:Demosite" target="_blank">Demosite</a> im Blue Engineering mediaWiki bearbeitet werden.
			</p>
			<h1>Eingabemaske für mediaWiki Artikel mit WYSIWYG-Editor</h1>
			<!-- form starts -->
			<?=
			$this->Html->script('tinymce/tinymce.min.js', [ 'block' => 'script' ] );
			
			echo $this->Form->create();
			
			echo $this->Form->hidden( 'mw_edittoken', [ 'value' => $data['editToken'] ] );
			
			echo $this->Form->input( 'mw_articleTitle', [ 'label' => 'Artikelname', 'value' => $data['articleTitle'] ] );
			
			echo $this->Form->textarea( 'mw_articleContent', [ 'value' => $data['articleText'] ] );
			
			//echo $this->Form->reset( 'Zurücksetzen', [ 'type' => 'reset' ] );
			echo $this->Form->submit( 'Speichern', [ 'type' => 'submit' ] );
			
			echo $this->Form->end();
			?>
			
			<!-- form ends -->
			
			<h1>Glossar</h1>
			
			<section id="glossar1">
			<h2>WYSIWYG</h2>
			<p>
			WYSIWYG steht für What you see is what you get.
			</p>
			</section>
			
			<section id="glossar2">
			<h2>Parser</h2>
			<p>
			Ein Parser ist ein Programm welches Textinhalte nach vorgegebenen Mustern durchsucht. Diese Muster stellen in den meisten Fällen eine definierte Syntax dar mit welcher bestimmte Steuerbefehle angesprochen werden.
			Eine bekannte Syntax ist beispielsweise der <a href="http://de.wikipedia.org/wiki/BBCode" target="_blank">BBCode</a> in diversen Foren mit welchem beispielsweise Text <b>fett</b> hervorgehoben werden kann.
			</p>
			</section>
		</div>
