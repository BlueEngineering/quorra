<!-- File: src/Template/Newschannels/index.ctp -->
	<div class="md-10">
		<h1>Auflistung der vorhandenen Nachrichtenkanäle</h1>
		<!--p>
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
		</p-->
		<p>
			<table class="table table-hover">
				<tr>
					<th class="text-center">#</th>
					<th class="text-center">Name des Kanals</th>
					<th class="text-center">mediaWiki Artikel ID</th>
				</tr>
				
				<!--  iterate starts -->
				<?php foreach( $newschannels as $newschannel ): ?>
				<tr>
					<td class="text-center"><?= $newschannel->id ?></td>
					<td class="text-center"><?= $newschannel->name ?></td>
					<td class="text-center"><?= $newschannel->mw_article_id ?></td>
				</tr>
				<?php endforeach; ?>
				<!--  iterate ends -->
				
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
	</div>