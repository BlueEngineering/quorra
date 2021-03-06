<?php
/************************************************************************************
 * MVC-Controller for administrated seminars.
 *
 * @author	Tim Jaap	<tim.jaap@mailbox.tu-berlin.de>
 * @version	1.0
 ************************************************************************************/
//
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use App\Component\MediawikiAPIComponent;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

class WorkspacesController extends AppController {
	// controller attributes
	public $paginate	= [
		'limit'		=> 1,
		'order'		=> [
			'Workspaces.name'	=> 'asc'
		]
	];
	
	/************************************************************************************
	 * initialize components and class width informations
	 *
	 ************************************************************************************/
	public function initialize() {
		parent::initialize();
		$this->loadComponent( 'MediawikiAPI' );
		$this->loadComponent( 'Paginator' );
	}
	
	/************************************************************************************
	 * lists all existing seminars where administrate with quorra
	 *
	 ************************************************************************************/
	public function index() {
		//
		$this->set( 'workspaces', $this->paginate()->toArray() );
	}
	
	/************************************************************************************
	 * create a new seminar with own namespace and usergroups in mediawiki
	 *
	 ************************************************************************************/
	public function add() {
		// set view variables
		$this->set( 'notice', '' );
		$this->set( 'cssInfobox', 'danger' );
		
		// is data sending by form?
		if( $this->request->is( 'post' ) ) {
			// validate given data
			
			
			$this->set( 'notice', 'Speichern Button wurde gedrückt' );
			$this->set( 'cssInfobox', 'success' );
		}		
		
		return;
	}
}
?>