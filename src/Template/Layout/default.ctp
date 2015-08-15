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

    
	<?= $this->Html->css( 'bootstrap.min.css' ) ?>
	<?= $this->Html->css( 'bootstrap-theme.min.css' ) ?>
	<?= $this->Html->css( 'quorra.css' ) ?>
	<?= ( $confApp == 1 ) ? $this->Html->css( 'cake.css' ) : ''; ?>
	
	<?= $this->Html->script( 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js' ) ?>
	<?= $this->Html->script( 'bootstrap.min.js' ) ?>

    <?= $this->fetch( 'meta' ) ?>
    <?= $this->fetch( 'css' ) ?>
    <?= $this->fetch( 'script' ) ?>
</head>
<body>	
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<li><?= $this->Html->link( 'Quorra', '/pages/home', [ 'class' => 'navbar-brand' ] ); ?></li>
			</div>
			
			<div id="navbarCollapse" class="collapse navbar-collapse">
				<?php
				if( !empty( $user ) ) {
				?>
					<ul class="nav navbar-nav navbar-left">
						<li>
						<?=
						$this->Html->link( 'Dashboard', [
								'controller'	=> 'users',
								'action'		=> 'index',
								'_full'			=> true
						] );
						?>
						</li>
						<li>
						<?=
						$this->Html->link( 'Demosite', [
								'controller'	=> 'demosite',
								'action'		=> 'index',
								'_full'			=> true
						] );
						?>
						</li>
					</ul>
					
					<ul class="nav navbar-nav navbar-right">
					
					<li class="dropdown">
						<a class="dropdown-toggle" id="dropdownWorkspaces" data-toggle="dropdown" aria-expanded="true">Verwaltung Arbeitsbereiche <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownWorkspaces">
							<?php
							echo '<li>' . $this->Html->link( 'Arbeitsbereiche anzeigen', [ 'controller' => 'workspaces', 'action' => 'index' ] ) . '</li>' .
								'<li class="divider"></li>' .
								'<li>' . $this->Html->link( 'Neuen Arbeitsbereich anlegen', [ 'controller' => 'workspaces', 'action' => 'add' ] ) . '</li>';
							?>
						</ul>
					</li>
					
					<li class="dropdown">
						<a class="dropdown-toggle" id="dropdownUsers" data-toggle="dropdown" aria-expanded="true">Benutzerverwaltung <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownUsers">
							<?php
							echo '<li>' . $this->Html->link( 'Benutzer_in anlegen', [ 'controller' => 'users', 'action' => 'add' ] ) . '</li>' .
								'<li>' . $this->Html->link( 'Benutzer_innen aus CSV Liste anlegen', [ 'controller' => 'users', 'action' => 'addbycsv/104' ] ) . '</li>' .
								'<li class="divider"></li>' .
								'<li>' . $this->Html->link( 'Benutzer_in bearbeiten', [ 'controller' => 'users', 'action' => 'edit/Testify' ] ) . '</li>'; // .
								//'<li class="divider"></li>' .
								//'<li>' . $this->Html->link( 'Benutzer_ingruppen verwalten', [ 'controller' => 'usergroups', 'action' => 'index' ] ) . '</li>' .
								//'<li>' . $this->Html->link( 'Benutzer_ingruppen anlegen', [ 'controller' => 'usergroups', 'action' => 'add' ] ) . '</li>';
							?>
						</ul>
					</li>
					<?php
					// experimente
					/*
					if( isset( $user->groups ) ) {
						$gs			= explode( ';', $user->groups );
						
						echo '<li class="dropdown">' .
							'<a class="dropdown-toggle" id="dropdownNews" data-toggle="dropdown" aria-expanded="true">Benutzer_innen <span class="caret"></span></a>' .
							'<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownUsers">';
					
						foreach( $gs as $g ) {
							if( $g == 'sysop' ) {
								echo '<li>' . $this->Html->link( 'Benutzer_in anlegen', [ 'controller' => 'users', 'action' => 'create' ] ) . '</li>';
								echo '<li>' . $this->Html->link( 'CSV hochladen', [ 'controller' => 'users', 'action' => 'createbycsv' ] ) . '</li>';
							}
						}
						
						echo '</ul>' .
							'</li>';
					}*/
					?>
					<!--li class="dropdown">
						<a class="dropdown-toggle" id="dropdownNews" data-toggle="dropdown" aria-expanded="true"><span class="glyphicon glyphicon-header"></span> Neuigkeiten <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownNews">
							<li><?= $this->Html->link( 'Übersicht anzeigen', '/pages/ph_news_overview' ); ?></li>
							<li><?= $this->Html->link( 'Neuigkeit anlegen', '/pages/ph_news_new' ); ?></li>
							<li class="divider"></li>
							<li><?= $this->Html->link( 'Nachrichtenkanäle anzeigen', '' ); ?></li>
							<li><?= $this->Html->link( 'Nachrichtenkanal anlegen', '' ); ?></li>
						</ul>
					</li-->
				
					<!--li class="dropdown">
						<a class="dropdown-toggle" id="dropdownContent" data-toggle="dropdown" aria-expanded="true"><span class="glyphicon glyphicon-list-alt"></span> Inhalte <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownContent">
							<li><?= $this->Html->link( '... Übersicht allgemein', '/pages/ph_content_general_overview' ); ?></li>
							<li><?= $this->Html->link( '... Neuen Artikel anlegen', '/pages/ph_content_new' ); ?></li>
							<li><?= $this->Html->link( '... Artikel bearbeiten', '/article' ); ?></li>
							<li class="divider"></li>
							<li><?= $this->Html->link( 'Übersicht aller Bausteine anzeigen', '/pages/ph_content_bricks_overview' ); ?></li>
							<li><?= $this->Html->link( 'Einen neuen Baustein anlegen', '/pages/ph_content_bricks_new' ); ?></li>
							<li class="divider"></li>
							<li><?= $this->Html->link( 'Übersicht aller Faktenkarten anzeigen', '/pages/ph_content_factcards_overview' ); ?></li>
							<li><?= $this->Html->link( 'Eine neue Faktenkarte anlegen', '/pages/ph_content_factcards_new' ); ?></li>
						</ul>
					</li-->
				
					<li><?= $this->Html->link( 'Logout', [ 'controller' => 'users', 'action' => 'logout', 'full_base' => true ] );  ?></li>
				
					<!--li class="dropdown">
						<a class="dropdown-toggle" id="dropdownCalendar" data-toggle="dropdown" aria-expanded="true"><span class="glyphicon glyphicon-calendar"></span> Kalendar <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownCalendar">
							<li><?= $this->Html->link( 'Kalendar anzeigen', '/pages/ph_calendar_overview' ); ?></li>
							<li class="divider"></li>
							<li><?= $this->Html->link( 'Neuen Termin anlegen', '/pages/ph_calendar_appointment' ); ?></li>
						</ul>
					</li-->
				
					<!--li class="dropdown">
						<a class="dropdown-toggle" id="dropdownCourse" data-toggle="dropdown" aria-expanded="true"><span class="glyphicon glyphicon-grain"></span> Kurse <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownCourse">
							<li><?= $this->Html->link( 'Alle Kurse anzeigen', '/pages/ph_course_overview' ); ?></li>
							<li class="divider"></li>
							<li><?= $this->Html->link( 'Eigene Kurse', '/pages/ph_course_my_overview', [ 'class' => 'text-strong' ] ); ?></li>
							<li><?= $this->Html->link( 'Seminar A', '/pages/ph_course_sem_a' ); ?></li>
							<li><?= $this->Html->link( 'Seminar B', '/pages/ph_course_sem_b' ); ?></li>
							<li><?= $this->Html->link( '...', '' ); ?></li>
						</ul>
					</li-->
				
					<!--li class="dropdown">
						<a class="dropdown-toggle" id="dropdownMediadatabase" data-toggle="dropdown" aria-expanded="true"><span class="glyphicon glyphicon-book"></span> Mediendatenbank <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownCourses">
							<li><?= $this->Html->link( 'Übersicht', '/pages/ph_mediadb_overview' ); ?></li>
							<li><?= $this->Html->link( 'Medium hinzufügen', '/pages/ph_mediadb_add' ); ?></li>
							<li><?= $this->Html->link( 'Suche', '/pages/ph_mediadb_search' ); ?></li>
						</ul>
					</li-->
				
					<!--li class="dropdown">
						<a class="dropdown-toggle" id="dropdownUsergroups" data-toggle="dropdown" aria-expanded="true"><span class="glyphicon glyphicon-user"></span> Benutzergruppen <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownUsergroups">
							<li><?= $this->Html->link( '... anzeigen', '/pages/ph_usergroups_overview' ); ?></li>
							<li><?= $this->Html->link( '... anlegen', '/pages/ph_usergroups_new' ); ?></li>
							<li class="divider"></li>
							<li><?= $this->Html->link( 'Rechtematrix anzeigen', '/pages/ph_usergroups_rightmatrix' ); ?></li>
						</ul>
					</li-->
				
					<!--li class="dropdown">
						<a class="dropdown-toggle" id="dropdownMyBlog" data-toggle="dropdown" aria-expanded="true"><span class="glyphicon glyphicon-edit"></span> Mein Blog <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMyBlog">
							<li><?= $this->Html->link( '... verwalten', '' ); ?></li>
							<li><?= $this->Html->link( 'Neuen Eintrag anlegen', '' ); ?></li>
						</ul>
					</li-->
				
					<!--li class="dropdown">
						<a class="dropdown-toggle" id="dropdownSettings" data-toggle="dropdown" aria-expanded="true"><span class="glyphicon glyphicon-pencil"></span> Profil <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownSettings">
							<li><?= $this->Html->link( '... bearbeiten', '/pages/ph_profile' ); ?></li>
							<li class="divider"></li>
							<li><?= $this->Html->link( 'Logout', '' ); ?></li>
						</ul>
					</li-->
				
					<!--li class="dropdown">
						<a href="dropdown-toggle" id="dropdownSystem" data-toggle="dropdown" aria-expaned="true"><span class="glyphicon glyphicon-wrench"></span> System <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownSystem">
							<li><?= $this->Html->link( 'mediaWiki Vanilla', '/pages/ph_settings_mediawiki' ); ?></li>
							<li><?= $this->Html->link( 'mediaWiki Erweiterungen verwalten', '/pages/ph_settings_extensions' ); ?></li>
							<li class="divider"></li>
							<li><?= $this->Html->link( 'Status der Serverdienste', '/pages/ph_services' ); ?></li>
							<li><?= $this->Html->link( 'Logfiles einsehen', '/pages/ph_view_logfiles' ); ?></li>
						</ul>
					</li-->
				
					<!--li class="dropdown">
						<a href="dropdown-toggle" id="dropdownHelp" data-toggle="dropdown" aria-expaned="true"><span class="glyphicon glyphicon-question-sign"></span> Hilfe <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownHelp">
							<li><?= $this->Html->link( 'Helpcenter', '/pages/ph_help_helpcenter' ); ?></li>
							<li><?= $this->Html->link( 'Probleme melden', '/pages/ph_help_problem' ); ?></li>
							<li class="divider"></li>
							<li><?= $this->Html->link( 'Feedback', '/pages/ph_help_feedback' ); ?></li>
							<li class="divider"></li>
							<li><?= $this->Html->link( 'Über Quorra', '/pages/ph_help_quorra_info' ); ?></li>
						</ul>
					</li-->
				</ul>
				
				<?php
				}
				?>
			</div>
		</div>
	</nav>
	
    <div class="container-fluid">
		<div class="row">
			<!--div class="col-sm-3 col-md-2 sidebar">
			Sidebar (later)
			</div-->
			<div class="col-sm-3 col-md-2">
			</div>
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
				<div class="row">
				<?= $this->fetch('content') ?>
				</div>
			</div>
		</div>
    </div>
</body>
</html>
