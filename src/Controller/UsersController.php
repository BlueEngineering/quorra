<?php
/**
 * MVC controller for quorra users based on mediawiki users
 *
 * @author		Tim Jaap <tim.jaap@mailbox.tu-berlin.de>
 * @version		1.0 (beta)
 */
 
// configure namespace
namespace App\Controller;

// load components
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Network\Session;
use Cake\ORM\TableRegistry;
use App\Component\MediawikiAPIComponent;


class UsersController extends AppController {
	/************************************************************************************
	 * init some settings and components
	 *
	 ************************************************************************************/
	public function initialize() {
		parent::initialize();
		
		// load mediawiki api component
		$this->loadComponent( 'MediawikiAPI' );
	}
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function beforeFilter( Event $event ) {
	}
	
	
	/************************************************************************************
	 * list existing mediawiki users
	 *
	 ************************************************************************************/
	public function index() {
		// init variables
		$user		= $this->Users->get( $this->Auth->user( 'id' ) );
		$groups		= '';
		
		// call wiki api to receive newest user informations
		$mwUserinfo	= $this->MediawikiAPI->mw_getUserinfos();
		
		//
		if( $mwUserinfo->query->userinfo->id > 0 ) {
			foreach( $mwUserinfo->query->userinfo->groups as $group ) {
				$groups		.= $group . ';';
			}
		}
		
		// copy received mediawiki userinformations in quorra user object
		$user->username		= $mwUserinfo->query->userinfo->name;
		$user->realname		= $mwUserinfo->query->userinfo->realname;
		$user->email		= $mwUserinfo->query->userinfo->email;
		$user->groups		= $groups;
		
		// save (=update) quorra user object to database
		$this->Users->save( $user );
		$this->Auth->setUser( array(
				'id'		=> $this->Auth->user( 'id' ),
				'username'	=> $user->username,
				'realname'	=> $user->realname,
				'email'		=> $user->email,
				'groups'	=> $user->groups
			)
		);
		
		$this->set( 'user', $user );
		$this->set( 'currMwUserinfos', $mwUserinfo );
	}
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function createbycsv() {
	}
	
	
	/************************************************************************************
	 * user authentication
	 *
	 ************************************************************************************/
	public function login() {
		// init view files
		$cssInfobox		= 'danger';
		$errorMessage	= '';
				
		if( $this->request->is('post') ) {
			// load quorra configurations
			$mw_conf		= Configure::read('quorra');
			
			// login to mediawiki over api
			$mwLogin		= $this->MediawikiAPI->mw_login( $this->request->data["username"], $this->request->data['password'] );
			
			// mediawiki login successful?
			if( $mwLogin->login->result == 'Success' ) {				
				// is wiki user in quorra database
				$query		= $this->Users->findById( $mwLogin->login->lguserid );
				
				if( $query->count() == 0 ) {
					// create new entity
					$user			= $this->Users->newEntity();
					
					// set entity data
					$user->id		= $mwLogin->login->lguserid;
					$user->username	= $mwLogin->login->lgusername;
					$user->realname	= '';
					$user->email	= '';
					$user->groups	= '';
					
					// save entity
					$this->Users->save( $user );
					
					// build array for auth componente
					$user			= array( 'id' => $mwLogin->login->lguserid, 'username' => $mwLogin->login->lgusername, 'realname' => '', 'email' => '', 'groups' => '' );
				} else {
					// receive data from database
					$user			= $query->first();
					
					// build array for auth componente
					$user			= array( 'id' => $user->id, 'username' => $user->lgusername, 'realname' => $user->realname, 'email' => $user->email, 'groups' => $user->groups );
				}
				
				// login user to cakephp app
				$user		= $this->Auth->setUser( $user );
								
				// redirect to start page
				return $this->redirect( $this->Auth->redirectUrl() );
			}
			
			// set view informations
			$errorMessage	= $mwLogin->login->result;
		}
		$this->set( 'cssInfobox', $cssInfobox );
		$this->set( 'errorMessage', $errorMessage );
	}
	
	
	/************************************************************************************
	 * logged current user out from quorra
	 *
	 ************************************************************************************/
	public function logout() {
		// send logout to mediawiki
		$this->MediawikiAPI->mw_logout();
		
		// call Auth to logout on cakephp
		return $this->redirect( $this->Auth->logout() );
	}
}
?>