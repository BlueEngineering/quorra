<?php
/************************************************************************************
 * MVC-Controller for news center
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

class NewsCenterController extends AppController {
	/************************************************************************************
	 * init the news center controller and all needed functions from other components
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
	}
	
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function newscenterChannelsCreate() {
	}
	
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function newscenterChannelsEdit() {
	}
	
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function newscenterChannelsDelete() {
	}
	
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function newscenterNewsList() {
	}
	
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function newscenterNewsView() {
	}
	
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function newscenterNewsCreate() {
	}
	
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function newscenterNewsEdit() {
	}
	
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function newscenterNewsDelete() {
	}
}
?>