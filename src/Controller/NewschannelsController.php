<?php
/************************************************************************************
 * MVC-Controller for news channels. A news center component
 *
 * @author	Tim Jaap	<tim.jaap@mailbox.tu-berlin.de>
 * @version	1.0
 ************************************************************************************/
//
namespace App\Controller;

//
use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Session;
use App\Component\MediawikiAPIComponent;
use Cake\ORM\TableRegistry;

class NewschannelsController extends AppController {
	/************************************************************************************
	 * init the news center controller and all needed functions from other components
	 *
	 ************************************************************************************/
	public function initialize() {
		parent::initialize();
		$this->loadComponent( 'MediawikiAPI' );
	}
	
	
	/************************************************************************************
	 * start point to jump into newschannel administration
	 *
	 ************************************************************************************/
	public function index() {
		// get all database entries from table newschannels
		$newschannels	= $this->Newschannels->find( 'all' );
		
		// put all entries to view template
		$this->set( compact( 'newschannels' ) );
	}
	
	
	/************************************************************************************
	 * method to call edit mask. it's can view a database entry or a template dataset
	 *
	 * @param	int		$id
	 ************************************************************************************/
	public function edit( $id ) {
		// is id given?
		if ( !isset ( $id ) ) {
			// get database entry with given id
			throw new NotFoundException('Could not find that post');
		}
		
		// get database entry with given id
		$newschannel	= $this->Newschannels->get( $id );
		
		// put entry to view template
		$this->set( compact( 'newschannel' ) );
	}
	
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function create() {
	}
	
	
	/************************************************************************************
	 * save a new or an existing news channel entry
	 *
	 * TODO: Verschieben bzw. Umbenennen eines Newschannels muss in allen mw Artikeln angepasst werden.
	 		-> Ansatz: Hole Liste mit Artikeln in welcher der News Channel eingebunden ist und ändere den Namen.
	 ************************************************************************************/
	public function save() {
		// temp. vars.
		$mw_conf				= Configure::read('quorra');
		$newsCenterMwPrefix		= 'NewsCenter:';
						
		// is this a new channel?
		if ( $this->request->data['channel_id'] > 0 ) {
			$this->set( 'actionname', 'bearbeiten' );
			$this->set( 'linkback', 'edit' );
		} else {
			$this->set( 'actionname', 'anlegen' );
			$this->set( 'linkback', 'create' );
		}
		
		// set default alert style on danger
		$this->set( 'alertstyle', 'alert-danger' );
		
		// isn't name set?
		if ( empty( $this->request->data['channel_name'] )  ) {
			$this->set( 'responseMessage', 'Es wurde kein Name für den Nachrichtenkanal angegeben.' );
			return;
		}
		
		// get page id
		$mw_pageId		= key( $this->MediawikiAPI->mw_getArticleByTitle( $newsCenterMwPrefix . $this->request->data['channel_name'] )->query->pages );
		
		// isn't exist a mediawiki article with this name? than create them
		if ( $mw_response < 1 ) {
			// get edit token
			$editToken		= $this->MediawikiAPI->mw_getEditToken()->query->tokens->csrftoken;
			
			// check edit token
			if ( $this->MediawikiAPI->mw_testEditToken( $editToken )->checktoken->result !== 'valid' ) {
				return;
			}
			
			// create mw article
			$mw_response	= $this->MediawikiAPI->mw_createArticle( ( $newsCenterMwPrefix . $this->request->data['channel_name'] ), ' ', ( 'News channel created by ' . $_COOKIE[$mw_conf['mediawiki']['cookieprefix'] . 'UserName'] . ' with Quorra.' ), $editToken );
			
			// catching error if exists
			if ( isset( $mw_response->error ) ) {
				// set alert box to danger
				$this->set( 'alertstyle', 'alert-danger' );
				// set error message with error code
				$this->set( 'responseMessage', 'Oops! Da ist leider etwas schief gelaufen.<br /><br />Es wurde folgende Fehlermeldung zurückgegeben: ' . $mw_response->error->code . '<br /><br />Bitte wende dich an eine_n der Systembetreuer_innen.';
				return;
			}
			
			// id from news article doesn't ok?
			if ( $mw_response->edit->pageid < 1 ) {
				$this->set( 'alertstyle', 'alert-danger' );
				$this->set( 'responseMessage', 'Es ist ein unbekannter Fehler aufgetreten! Bitte wende dich an eine_n der Systembetreuer_innen!' );
				return;
			}
			
			// set page id from new page
			$mw_pageId	= $mw_response->edit->pageid;
		}
		
		// create a new entity
		//$newschannelsTable	= TableRegistry::get( 'Newschannels' );
		$newschannel	= $this->Newschannels->newEntity();
		
		// seting data
		//$newschannel->id				= '';
		$newschannel->name				= $this->request->data['channel_name'];
		$newschannel->mw_article_id		= $mw_pageId;
		
		// check if data are saving
		if ( $this->Newschannels->save( $newschannel ) ) {
			// The $article entity contain the id now
    		$id		= $newschannel->id;
			
			// set view data
			$this->set( 'alertstyle', 'alert-success' );
			$this->set( 'responseMessage', 'Der Nachrichtenkanal wurde gespeichert.' );
		}
	}
	
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function delete() {
	}
}
?>