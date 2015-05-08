<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--title><?= $this->fetch('title') ?></title-->
	<title>Quorra - mediaWiki Management Suite by Blue Engineering Webteam</title>
	<link href="favicon.png" type="image/x-icon" rel="shortcut icon"/>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>
	<?= $this->Html->css('bootstrap.min.css') ?>
	<?= $this->Html->css('bootstrap-theme.min.css') ?>
	
	<?= $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js') ?>
	<?= $this->Html->script('bootstrap.min.js') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>	
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-header">
			<button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a href="#" class="navbar-brand"><!--img class="glyphicon" src="favicon.png" /--> Quorra</a>
		</div>
		<div id="navbarCollapse" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li>
					<a href="#desh"><span class="glyphicon glyphicon-home"></span> Dashboard</a>
				</li>
				
				<li class="dropdown">
					<a class="dropdown-toggle" id="dropdownNews" data-toggle="dropdown" aria-expanded="true"> Neuigkeiten <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownNews">
						<li><a href="">... verwalten</a></li>
						<li><a href="">... hinzufügen</a></li>
					</ul>
				</li>
				
				<li class="dropdown">
					<a class="dropdown-toggle" id="dropdownContent" data-toggle="dropdown" aria-expanded="true">Inhalte <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownContent">
						<li><a href="article"><b>Allgemein</b></a></li>
						<li><a href="">... auflisten</a></li>
						<li><a href="">... erstellen</a></li>
						<li class="divider"></li>
						<li><a href=""><b>Bausteine</b></a></li>
						<li><a href="">... auflisten</a></li>
						<li><a href="">... erstellen</a></li>
						<li class="divider"></li>
						<li><a href=""><b>Faktenkarten</b></a></li>
						<li><a href="">... auflisten</a></li>
						<li><a href="">... erstellen</a></li>
					</ul>
				</li>
				
				<li class="dropdown">
					<a class="dropdown-toggle" id="dropdownCalendar" data-toggle="dropdown" aria-expanded="true"><span class="glyphicon glyphicon-calendar"></span> Kalendar <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownCalendar">
						<li><a href="#">... anzeigen</a></li>
						<li class="divider"></li>
						<li><a href="#">Termin anlegen</a></li>
					</ul>
				</li>
				
				<li class="dropdown">
					<a class="dropdown-toggle" id="dropdownUsergroups" data-toggle="dropdown" aria-expanded="true">Benutzergruppen <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownUsergroups">
						<li><a href="#">... auflisten</a></li>
						<li><a href="#">... erstellen</a></li>
						<li class="divider"></li>
						<li><a href="#">Rechtematrix anzeigen</a></li>
					</ul>
				</li>
				
				<li class="dropdown">
					<a class="dropdown-toggle" id="dropdownSettings" data-toggle="dropdown" aria-expanded="true"><span class="glyphicon glyphicon-list-alt"></span> Einstellungen <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownSettings">
						<li><a href="#">Profil</a></li>
						<li class="divider"></li>
						<li><a href="#">mediaWiki Einstellungen</a></li>
					</ul>
				</li>
				
				<li>
					<a href="#"><span class="glyphicon glyphicon-off"></span> Logout</a>
				</li>
				
				<li>
					<a href="#"><span class="glyphicon glyphicon-question-sign"></span> Hilfe</a>
				</li>
			</ul>
		</div>
	</nav>
	
    <div class="container">
		<div class="row">
			<div class="col-md1"></div>
			<div class="col-md10">
				<div class="row"><p>&nbsp;</p><p>&nbsp;</p></div>
			</div>
			<div class="col-md1"></div>
		</div>
		<div class="row">
			<div class="col-md1">&nbsp;</div>
			<div class="col-md10">
				<div id="content">
					<div class="row">
						<?= $this->fetch('content') ?>
					</div>
				</div>
			</div>
			<div class="col-md1">&nbsp;</div>
		</div>
    </div>
</body>
</html>
