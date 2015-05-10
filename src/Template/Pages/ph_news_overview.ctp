	<div class="md-10">
		<h1>Übersicht: Neuigkeiten</h1>
		<p>
			<nav>
				<div class="input-group-btn">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expaned="true"> Filter... <span class="caret"></span></button>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#">Action</a></li>
						<li><a href="#">Another action</a></li>
						<li><a href="#">Something else here</a></li>
						<li class="divider"></li>
						<li><a href="#">Separated link</a></li>
					</ul>
				</div>
				<div class="input-group-btn">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expaned="true"> Filter... <span class="caret"></span></button>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#">Action</a></li>
						<li><a href="#">Another action</a></li>
						<li><a href="#">Something else here</a></li>
						<li class="divider"></li>
						<li><a href="#">Separated link</a></li>
					</ul>
				</div>
			</nav>
		</p>
		<p>
			<strong>Hier muss noch eine Steuerleiste hin um nach bestimmten Eigenschaften zu filtern bzw. eine bestimmte Nachricht / Neuigkeit zu finden.</strong>
		</p>
		<p>
			<table class="table table-hover">
				<tr>
					<th class="text-center">#</th>
					<th class="text-center">Titel</th>
					<th class="text-center">Urheber</th>
					<th class="text-center">erstellt am</th>
					<th class="text-center">Nachrichtenkanal</th>
					<th class="text-center">Preview</th>
				</tr>
				<tr>
					<td class="text-center">1</td>
					<td class="text-center"><?= $this->Html->link( 'Dies ist ein Beispieltitel', '' ); ?></td>
					<td class="text-center"><?= $this->Html->link( 'Yennefer of Vengerberg', '' ); ?></td>
					<td class="text-center">01.01.2000 00:00</td>
					<td class="text-center">Allgemein</td>
					<td class="text-center"><a href=""><span class="glyphicon glyphicon-eye-open"></span></a></td>
				</tr>
				<tr>
					<td class="text-center">2</td>
					<td class="text-center"><?= $this->Html->link( 'Ein weiterer Beispieltitel für eine News die nicht existiert', '' ); ?></td>
					<td class="text-center"><?= $this->Html->link( 'Sigismund Dijkstra', '' ); ?></td>
					<td class="text-center">15.08.2015 12:23</td>
					<td class="text-center">Allgemein, Blaupunkt</td>
					<td class="text-center"><a href=""><span class="glyphicon glyphicon-eye-open"></span></a></td>
				</tr>
				<tr>
					<td class="text-center">3</td>
					<td class="text-center"><?= $this->Html->link( 'Ob das noch was wird?', '' ); ?></td>
					<td class="text-center"><?= $this->Html->link( 'Pipi Langstrumpf', '' ); ?></td>
					<td class="text-center">01.01.2000 00:00</td>
					<td class="text-center">Allgemein, NoSpy</td>
					<td class="text-center"><a href=""><span class="glyphicon glyphicon-eye-open"></span></a></td>
				</tr>
				<tr>
					<td class="text-center">4</td>
					<td class="text-center"><?= $this->Html->link( 'Der verpasste Weltuntergang', '' ); ?></td>
					<td class="text-center"><?= $this->Html->link( 'Lord Ewylkas', '' ); ?></td>
					<td class="text-center">23.12.2012 23:59</td>
					<td class="text-center">Allgemein, Geheim!</td>
					<td class="text-center"><a href=""><span class="glyphicon glyphicon-eye-open"></span></a></td>
				</tr>
				<tr>
					<td class="text-center">...</td>
					<td class="text-center">...</td>
					<td class="text-center">...</td>
					<td class="text-center">...</td>
					<td class="text-center">...</td>
					<td class="text-center">...</td>
				</tr>
			</table>
		</p>
		<p>
			<nav class="text-center">
				<ul class="pagination">
					<li><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
					<li><a href="#">1</a></li>
					<li><a href="#">2</a></li>
					<li><a href="#">3</a></li>
					<li><a href="#">4</a></li>
					<li><a href="#">5</a></li>
					<li><a href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
				</ul>
			</nav>
		</p>
		<p>
		<strong>Dies ist bisher nur eine Beispielseite ohne Funktion!</strong>
		</p>
	</div>