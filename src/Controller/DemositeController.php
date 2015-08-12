<?php
/************************************************************************************
 * MVC-Controller for Demosite to testing Quorra WYSIWYG Editor without a
 * mediawiki account.
 *
 * @author	Tim Jaap	<tim.jaap@mailbox.tu-berlin.de>
 * @version	1.0
 ************************************************************************************/
//
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use App\Component\MediawikiAPIComponent;
use Cake\Network\Session;
use Cake\Event\Event;

class DemositeController extends AppController {
	//
	/************************************************************************************
	 * Method to load config files in initializing phase
	 *
	 ************************************************************************************/
	public function initialize() {
		parent::initialize();
		$this->loadComponent( 'MediawikiAPI' );
	}
	
	public function beforeFilter( Event $event ) {
		$this->Auth->allow();
	}
	
	
	/************************************************************************************
	 * Method to initialise demo function
	 *
	 ************************************************************************************/
	public function index() {
		// read mediawiki settings from config file
		$mw_conf		= Configure::read('quorra');
		$demositeTitle	= "Demo:Demosite";
		
		// login with user
		/*
		if( !$this->Auth->user( 'id' ) ) {
			$this->MediawikiAPI->mw_login( $mw_conf['mediawiki']['demouser'], $mw_conf['mediawiki']['demopass'] );
			//$this->redirect( [ 'controller' => 'demosite', 'action' => 'index' ] );
		}
		*/
		
		// get demosite id
		$demositeId					= key( $this->MediawikiAPI->mw_getArticleByTitle( $demositeTitle )->query->pages );
						
		// workaround with [*] problem
		$textfield					= '*';
		
		// receive edit token
		$formdata['editToken']		= $this->MediawikiAPI->mw_getEditToken()->query->tokens->csrftoken;
		
		if ( $demositeId != -1 ) {
			// get article informations
			$tempArticle				= $this->MediawikiAPI->mw_getArticleByID( $demositeId );
			
			$formdata['curTimestamp']	= $tempArticle->curtimestamp;
			$formdata['articleId']		= $demositeId;
			$formdata['articleTitle']	= $tempArticle->query->pages->$demositeId->title;
			//$formdata['magicWords']		= $this->MediawikiAPI->mw_parseMagicWords( $tempArticle->query->pages->$demositeId->revisions[0]->$textfield );
			$formdata['articleText']	= $this->MediawikiAPI->mw_parsingWikisyntaxToHtml( $tempArticle->query->pages->$demositeId->revisions[0]->$textfield );
						
		} else {
			// create timestamp in mediawiki format
			$formdata['curTimestamp']	= date( 'Y-m-d' ) . "T" . date( 'H:i:s' ) . "Z";
			$formdata['articleId']		= "";
			$formdata['articleTitle']	= $demositeTitle;
			$formdata['articleText']	= "<p>Dies ist ein Beispieltext für die Demosite, da diese bisher noch nicht exitistiert.</p>";
		}
		
		// set template datas
		$this->set( 'data', $formdata );
		$this->set( 'url_demosite', 'http://' . $mw_conf['mediawiki']['url'] . '/index.php/Demo:Demosite' );
		$this->set( 'quorra_src', 'http://' . $mw_conf['srclink'] );
	}
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function edit() {
		// are data posted?
		if ( !empty( $this->request->data ) ) {
			// are article title and content doesn't empty?
			if ( !empty( $this->request->data['mw_articleTitle'] ) && !empty( $this->request->data['mw_articleContent'] ) ) {
				// Debugging line
				//$this->set( 'result', $this->MediawikiAPI->mw_parsingHtmlToWikisyntax( $this->request->data['mw_articleContent'] ) );
				
				// create a new page or edit an existing page?
				if ( $this->request->data['mw_articleId'] > 0 ) {
					$result		= $this->MediawikiAPI->mw_editArticle(
						$this->request->data['mw_articleId'],
						$this->request->data['mw_articleTitle'], 
						$this->MediawikiAPI->mw_parsingHtmlToWikisyntax( $this->request->data['mw_articleContent'] ), 
						'cakePHP quorra demo', 
						$this->request->data['mw_editToken'], 
						$this->request->data['mw_curTimestamp']
					);
				} else {
					$token		= $this->MediawikiAPI->mw_getEditToken()->query->tokens->csrftoken;
					$result		= $this->MediawikiAPI->mw_createArticle(
						$this->request->data['mw_articleTitle'], 
						$this->MediawikiAPI->mw_parsingHtmlToWikisyntax( $this->request->data['mw_articleContent'] ), 
						'cakePHP quorra demo', 
						$this->request->data['mw_editToken']
						// $this->request->data['mw_curTimestamp']
					);
				}
				
				// error message receive?
				if ( isset( $result->error->code ) ) {
					$editRes	= $result->error->code;
				}
				
				// no error message but a result?
				if ( isset( $result->edit->result ) ) {
					$editRes	= $result->edit->result;
				}
				
				// set alert style
				$this->set( 'alertstyle', 'alert-danger' );
				
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
					case 'Success':
						$this->set( 'result', 'Glückwunsch! Der Artikel wurde erfolgreich bearbeitet!' );
						$this->set( 'alertstyle', 'alert-success' );
						break;
					default:
						$this->set( 'result', 'Ein nicht definierter Fall ist aufgetreten. Bitte wende dich an die Systembetreuer_innen.' );
						$this->set( 'alertstyle', 'alert-warning' );
						break;
				}
			}
		} else {
			$this->set( 'result', 'Es wurden keine Daten übergeben.' );
			$this->set( 'alertstyle', 'alert-danger' );
		}
	}
}
?>