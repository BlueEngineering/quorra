<?php
/************************************************************************************
 * MVC-Controller of articles
 *
 * @author	Tim Jaap	<tim.jaap@mailbox.tu-berlin.de>
 * @version	1.0
 ************************************************************************************/
//
namespace App\Controller;

use	App\Controller\AppController;
use Cake\Core\Configure;
use App\Component\MediawikiAPIComponent;

class ArticleController extends AppController {
	//
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function initialize() {
		parent::initialize();
		$this->loadComponent( 'MediawikiAPI' );
	}
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function index() {
		// initialise
		$mw_conf	= Configure::read('quorra');
		
		// workaround with [*] problem
		//$testingPageId				= 549;
		$testingPageId				= 554;
		$textfield					= '*';
		
		
		// login with user
		$this->MediawikiAPI->mw_login( $mw_conf['mediawiki']['demouser'], $mw_conf['mediawiki']['demopass'] );
		
		// get article informations
		$tempArticle				= $this->MediawikiAPI->mw_getArticleByID( $testingPageId );
		
		
		echo '<br /><br /><br />';
		echo '<pre>';
		print_r($tempArticle);
		echo '</pre>';
		
		$formdata['editToken']		= $this->MediawikiAPI->mw_getEditToken()->query->tokens->csrftoken;
		$formdata['curTimestamp']	= $tempArticle->curtimestamp;
		$formdata['articleId']		= $testingPageId;
		$formdata['articleTitle']	= $tempArticle->query->pages->$testingPageId->title;
		$formdata['articleText']	= $tempArticle->query->pages->$testingPageId->revisions[0]->$textfield;
		
		$this->set( 'data', $formdata );
	}
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function edit() {
		//
		if ( !empty( $this->request->data ) ) {
			//
			if ( !empty( $this->request->data['mw_articleTitle'] ) && !empty( $this->request->data['mw_articleContent'] ) ) {
				//
				$token		= $this->MediawikiAPI->mw_getEditToken()->query->tokens->csrftoken;
				$result		= $this->MediawikiAPI->mw_editArticle( $this->request->data['mw_articleId'], $this->request->data['mw_articleTitle'], $this->request->data['mw_articleContent'], 'cakePHP quorra testing', $token, $this->request->data['mw_curTimestamp'] );
				
				// error message receive?
				if ( isset( $result->error->code ) ) {
					$editRes	= $result->error->code;
				}
				
				// no error message but a result?
				if ( isset( $result->edit->result ) ) {
					$editRes	= $result->edit->result;
				}
				
				switch( $editRes ) {
					case 'notitle':
						$this->set( 'result', 'Es wurde kein Titel für den Artikel übergeben.' );
						break;
					case 'notext':
						$this->set( 'result', 'Es wurde kein Inhalt übergeben.' );
						break;
					case 'notoken':
						$this->set( 'result', 'Es wurde kein Bearbeitungstoken übergeben.' );
						break;
					case 'invalidsection':
						$this->set( 'result', 'Der Abschnitt wurde nicht angegeben.' );
						break;
					case 'protectedtitle':
						$this->set( 'result', 'Der gewählte Titel ist geschützt und kann daher nicht verwendet werden.' );
						break;
					case 'cantcreate':
						$this->set( 'result', 'Die Seite konnte wegen fehlenden Rechten nicht angelegt werden.' );
						break;
					case 'cantcreate-anon':
						$this->set( 'result', 'Anonymen Benutzern ist das Anlegen neuer Artikel nicht erlaubt. Bitte logge dich ein!' );
						break;
					case 'articleexists':
						$this->set( 'result', 'Der Artikel existiert bereits.' );
						break;
					case 'spamdetected':
						$this->set( 'result', 'Scheinbar hast du in deinem Inhalt Spam Muster drin.' );
						break;
					case 'noedit':
						$this->set( 'result', 'Du hast keine Berechtigung diese Seite zu bearbeiten. Bitte wende dich an die Systembetreuer_innen.' );
						break;
					case 'badtoken':
						$this->set( 'result', 'Der übergebene Token ist ungültig. Bitte wende dich an die Systembetreuer_innen.' );
						break;
					/*
					case '':
						$this->set( 'result', '' );
						break;
					
					*/
					case 'Success':
						$this->set( 'result', 'Glückwunsch! Der Artikel wurde erfolgreich bearbeitet!' );
						break;
					default:
						$this->set( 'result', 'Scheinbar alles ok?!' );
						break;
				}
			}
		} else {
			$this->set( 'result', 'Es wurden keine Daten übergeben.' );
		}
		
		//$this->set( 'data', $data );
	}
}
?>