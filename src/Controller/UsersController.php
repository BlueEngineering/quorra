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
//use Cake\Network\Session;
use Cake\ORM\TableRegistry;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use App\Component\MediawikiAPIComponent;
use App\Component\PasswordGeneratorComponent;
use App\Component\EmailSendingComponent;


class UsersController extends AppController {
	/************************************************************************************
	 * init some settings and components
	 *
	 ************************************************************************************/
	public function initialize() {
		parent::initialize();
		
		// load mediawiki api component
		$this->loadComponent( 'MediawikiAPI' );
		$this->loadComponent( 'PasswordGenerator' );
		$this->loadComponent( 'EmailSending' );
	}
	
	/************************************************************************************
	 * called actions before controller functions executes.
	 *
	 ************************************************************************************/
	public function beforeFilter( Event $event ) {
		// receive user informations and ACL control
		if( null !== $this->Auth->user() ) {
			// receive userinformation from mediawiki
			$userInfos		= $this->MediawikiAPI->mw_getMyUserinfos()->query->userinfo;
			
			// create temporary array for usergroups
			$groups			= array();
			
			// remove allusers (= *-symbol) from usergrouplist and sort to reindex the array
			unset( $userInfos->groups[ array_search( '*', $userInfos->groups ) ] );
			unset( $userInfos->groups[ array_search( 'autoconfirmed', $userInfos->groups ) ] );
			sort( $userInfos->groups );
			
			// set missing user values in auth session
			if( !empty( $userInfos->realname ) ) {
				$this->request->session()->write( 'Auth.User.realname', $userInfos->realname );
			}
			
			if( !empty( $userInfos->email ) ) {
				$this->request->session()->write( 'Auth.User.email', $userInfos->email );
			}
			
			// load usergroup entites
			foreach( $userInfos->groups as $group ) {
				// search current mw usergroup in database and add to usergroup array
				array_push( $groups, $this->Users->Groups->get( $group ) );
			}
			
			// set usergroups for ACL
			$this->request->session()->write( 'Auth.User.groups', $groups );
		
			// control variable 	
			$isAllow	= false;
			
			for( $i = 0; $i < count( $this->Auth->user()["groups"] ); $i++ ) {
				if( $this->Auth->user()["groups"][$i]["users"] == 1 ) {
					$isAllow	= true;
					break;
				}
			}
			
			switch( $this->request->action ) {
				case 'index':
					break;
				case 'add':
					if( $isAllow != true ) {
						return $this->redirect( [ 'controller' => 'users', 'action' => 'index' ] );
					}
					break;
				case 'addbycsv':
					if( $isAllow != true ) {
						return $this->redirect( [ 'controller' => 'users', 'action' => 'index' ] );
					}
					break;
				case 'create':
					if( $isAllow != true ) {
						return $this->redirect( [ 'controller' => 'users', 'action' => 'index' ] );
					}
					break;
				case 'edit':
					if( $isAllow != true ) {
						return $this->redirect( [ 'controller' => 'users', 'action' => 'index' ] );
					}
					break;
				case 'listusers':
					if( $isAllow != true ) {
						return $this->redirect( [ 'controller' => 'users', 'action' => 'index' ] );
					}
					break;
			}
		}
	}
	
	/************************************************************************************
	 * dashboard of current user.
	 *
	 ************************************************************************************/
	public function index() {
		// init variables
		$user		= $this->Auth->user();
		
		//echo $user->groups[0]->name;
		
		// set view variables
		$this->set( 'user', $user );
	}
	
	/************************************************************************************
	 * list all users
	 *
	 ************************************************************************************/
	public function listusers() {
		//
		$this->set( 'userData', '' );
	}
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function edit( $username ) {
		
		// receive userinformations from mw
		$userData		= $this->MediawikiAPI->mw_getUserinfos( $username )->query->users[0];
		$groups			= array();
		
		// delete alluser (*-symbole) and autoconfirmed usergroups
		unset( $userData->groups[ array_search( '*', $userData->groups ) ] );
		unset( $userData->groups[ array_search( 'autoconfirmed', $userData->groups ) ] );
		unset( $userData->groups[ array_search( 'user', $userData->groups ) ] );
		
		// receive usergrouplist
		$ugroups		= $this->Users->Groups->find( 'all' )->all()->toArray();		
				
		for( $i = 0; $i < count( $ugroups ); $i++ ) {
			// remove group user from array
			if( $ugroups[$i]["name"] != 'user' ) {
				if( in_array( $ugroups[$i]["name"], $userData->groups ) ) {
					array_push( $groups, array( 'label' => $ugroups[$i]["label"], 'name' => $ugroups[$i]["name"], 'checked' => true ) );
				} else {
					array_push( $groups, array( 'label' => $ugroups[$i]["label"], 'name' => $ugroups[$i]["name"], 'checked' => false ) );
				}
			}
		}
		
		// delete last , in groups string
		$user				= array( 'name'	=> $userData->name, 'groups' => $groups );
		
		// is request sending?
		if( $this->request->is( 'post' ) ) {
			// temporary group control variables
			$addGroups		= '';
			$removeGroups	= '';
			
			
			echo '<pre>';
			print_r( $this->request->data );
			echo '</pre>';
			
			// search adding and removing groups
			foreach( $this->request->data["group"] as $grpname => $grpvalue ) {
				//
				if( $grpvalue == true  ) {
					$addGroups		.= $grpname . '|';
				} else {
					$removeGroups	.= $grpname . '|';
				}
			}
			
			// delete last symbol from strings
			$addGroups			= substr( $addGroups, 0, -1 );
			$removeGroups		= substr( $removeGroups, 0, -1 );
			
			// call mediawiki api
			$this->MediawikiAPI->mw_editUserrights( $this->request->data["username"], $addGroups, $removeGroups, $this->request->data["urtoken"] );
			
			// reload page with redirect
			$this->redirect( [ 'controller' => 'users', 'action' => 'edit/' . $username ] );
		}
		
		// call userrighttoken
		$user["urtoken"]	= $urtoken	= $this->MediawikiAPI->mw_getUserrightToken()->query->tokens->userrightstoken;
		
		$this->set( 'userData', $user );
	}	
	
	/************************************************************************************
	 * create an user and send email if mailaddress set.
	 *
	 ************************************************************************************/
	protected function create( $username, $email, $realname, $groups, $seminar = '', $token, $mailsender, $mailsubject, $mailtext ) {
		// generate a random password
		$password	= $this->PasswordGenerator->generate_password( 16 );
		
		
		// load quorra configurations
		$mw_conf		= Configure::read('quorra');
				
		// call mediawiki API function to create an useraccount
		$mwAnswer	= $this->MediawikiAPI->mw_createUseraccount( $username, $password, $email, $realname );
		
		// creating not successful?
		if( !empty( $mwAnswer->error ) || $mwAnswer->createaccount->result != 'Success' ) {
			return $mwAnswer->error->info;
		}
		
		// is email given?
		if( !empty( $email ) ) {
			// check if name set
			if ( empty( $realname ) ) {
				$name	= $username;
			} else {
				$name	= $realname;
			}
			
			// call sending Mail function
			$this->EmailSending->send_email(
				$mailsender,
				'Blue Engineering - Quorra mediaWiki management system',
				$email,
				$mailsubject,
				$this->EmailSending->parsing_mailtext(
					$mailtext,
					array(
						$name,
						$username,
						$password,
						$mw_conf['mediawiki']['scheme'] . "://" . $mw_conf['mediawiki']['url'] . "/index.php/" . $seminar,
						$mw_conf['mediawiki']['scheme'] . "://" . $mw_conf['mediawiki']['url']
					)
				)
			);
		}
		
		// groups given?
		if( !empty( $groups ) ) {
			$this->MediawikiAPI->mw_editUserrights( $username, $groups, '', $token );
		}
		
		return $mwAnswer->createaccount->result;
	}
	
	
	/************************************************************************************
	 * called and filled a mask to create a new user and submit mask datas to
	 * create() where call mediawiki API function to create a new user.
	 *
	 ************************************************************************************/
	public function add() {
		// init view variables
		// ---
		
		// receive userright token
		$urtoken	= $this->MediawikiAPI->mw_getUserrightToken()->query->tokens->userrightstoken;
		$this->set( 'urtoken', $urtoken );
		
		// receive usergrouplist
		$ugroups		= $this->Users->Groups->find( 'all' )->all()->toArray();
		$this->set( 'ugroups', $ugroups );
		
		if( $this->request->is( 'post' ) ) {
			// required fields empty?
			if( empty( $this->request->data["username"] ) || empty( $this->request->data["email"] ) ) {
				$this->set( 'notice', 'Es wurden nicht alle Pflichtfelder ausgefüllt. Pflichtfelder sind all jene Felder die kein (optional) Vermerk tragen.' );
				$this->set( 'cssInfobox', 'danger' );
				return;
			}
			
			// usergroups
			$addGroups		= '';
						
			foreach( $this->request->data["group"] as $grpname => $grpvalue ) {
				if( $grpvalue == true ) {
					$addGroups	.= $grpname . '|';
				}
			}
			
			$addGroups		= substr( $addGroups, 0, -1 );
			
			// create new mediawiki user			
			$result		= $this->create(
				$this->request->data["username"],
				$this->request->data["email"],
				$this->request->data["realname"],
				$addGroups,
				'',
				$this->request->data["urtoken"],
				$this->request->data["mailsender"],
				$this->request->data["mailsubject"],
				$this->request->data["mailtext"]
			);
			
			
			if( $result != 'Success' ) {
				$this->set( 'notice', 'Beim Anlegen des Benutzer_inaccounts ist ein Fehler aufgetreten.</p><p>' . $result );
				$this->set( 'cssInfobox', 'danger' );
				return;
			}
			
			$this->set( 'notice', 'Der / Die Benutzer_in wurde erfolgreich angelegt. Er / Sie wurde via E-Mail informiert.' );
			$this->set( 'cssInfobox', 'success' );
			return;
		}
		
		// set view variables
		$this->set( 'notice', '' );
		$this->set( 'cssInfobox', '' );
	}
	
	
	/************************************************************************************
	 * filled and called a mask where can upload a csv file with members. function
	 * build a username and call creating function to submit users in mediawiki.
	 *
	 ************************************************************************************/
	public function addbycsv( $semid ) {
		// init view variables
		$notice			= array();
		$error			= '';
		
		// receive userright token
		$urtoken	= $this->MediawikiAPI->mw_getUserrightToken()->query->tokens->userrightstoken;
		$this->set( 'urtoken', $urtoken );
		
		// given data from form?
		if( $this->request->is( 'post' ) ) {
			// sending file error?
			switch( $this->request->data["csvfile"]["error"] ) {
				case 1:
					$this->set( 'error', 'Die Größe der übermittelte Datei ist größer als vom Server gestattet.' );
					return;
				case 2:
					$this->set( 'error', 'Die Größe der übermittelten Datei überschreitet die in der HTML-Spezifikation festgelegte Größe.' );
					return;
				case 3:
					$this->set( 'error', 'Die Datei konnte nur teilweise übermittelt werden. Bitte versuchen Sie es erneut.' );
					return;
				case 4:
					$this->set( 'error', 'Es wurde keine Datei hochgeladen.' );
					return;
				case 5:
					$this->set( 'error', '' );
					return;
				case 6:
					$this->set( 'error', 'Es ist kein temporäres Verzeichnis zum Zwischenspeichern konfiguriert. Bitte informieren Sie Ihre_n Admin_a.' );
					return;
				case 7:
					$this->set( 'error', 'Die Datei konnte nicht gespeichert werden. Bitte informieren Sie Ihre_n Admin_a.' );
					return;
				case 8:
					$this->set( 'error', 'Eine PHP-Erweiterung hat den Uploadvorgang unterbrochen. Bitte informieren Sie Ihre_n Admin_a.' );
					return;
			}
			
			// is csv file?
			$filetype	= explode( '.', $this->request->data["csvfile"]["name"] );
			
			if( $filetype[ count($filetype)-1 ] != 'csv' ) {
				$this->set( 'error', 'Die Dateiendnung muss .csv sein. Bitte überprüfen Sie die ausgewählte Datei und versuchen Sie es erneut.' );
				return;
			}
			
			// init file handling
			$file		= new File( $this->request->data["csvfile"]["tmp_name"] );
			$file->open();
			$fdata		= $file->read();
			$csvData	= array();
						
			// parse csv file
			foreach( str_getcsv( $fdata, "\n" ) as $csvrow ) {
				$csvrow	= str_getcsv( $csvrow, ',' );
				array_push( $csvData, $csvrow );
			}
			
			// get seminar informations
			$seminar	= $this->Users->Seminars->find( 'all' )->where( [ 'Seminars.id =' => $semid ] )->toArray();
			
			// create username and build 
			for( $i = 0; $i < count( $csvData ); $i++ ) {
				// username pattern: <forename>.<first 2 chars of surname>.<semester>
				$username	= $csvData[$i][0] . '.' . substr( $csvData[$i][1], 0, 3 );
				
				if( !empty( $this->request->data["semname"] ) ) {
					$username	.= '.' . $this->request->data["semname"];
				}
				
				// create usergroup string
				$groups		= '';
				$urgroups	= explode( ',', $seminar[0]["memgrp"] );
				
				foreach( $urgroups as $group ) {
					$groups	.= $group . '|';
				}
				
				$groups		= substr( $groups, 0, -1 );
				
				// create account
				$result		= $this->create(
					$username,
					$csvData[$i][2],
					$csvData[$i][0] . ' ' . $csvData[$i][1],
					$groups,
					$seminar[0]["namespace"] . ":Start",
					$this->request->data["urtoken"],
					$seminar[0]["mailsender"],
					$seminar[0]["mailsubject"],
					$seminar[0]["mailtext"]
				);
				
				if( $result != 'Success' ) {
					$cssResult	= 'danger';
				} else {
					$cssResult	= 'success';
				}
				
				array_push( $notice, array( 'name' => $csvData[$i][0] . ' ' . $csvData[$i][1], 'cssResult' => $cssResult, 'result' => $result ) );
			}
			
			// close and delete file
			$file->close();
			$file->delete();
			
			$this->set( 'notice', $notice );
			$this->set( 'error', $error );
			return;
		}
		
		// set view variables
		$this->set( 'notice', $notice );
		$this->set( 'error', $error );
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
				// create and set array for auth->user()
				$user	= array(
					'id'			=> $mwLogin->login->lguserid,
					'username'	=> $mwLogin->login->lgusername,
					'realname'	=> '',
					'email'		=> '',
					'groups'		=> array()
				);
				
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
		
		//
		//unset( $_COOKIE[$mw_conf['mediawiki']['cookieprefix'] . "_session"] );
		/*unset( $mw_conf['mediawiki']['cookieprefix'] . "UserID" );
		unset( $mw_conf['mediawiki']['cookieprefix'] . "UserName" );
		unset( $mw_conf['mediawiki']['cookieprefix'] . "Token" );
		*/
		// call Auth to logout on cakephp
		return $this->redirect( $this->Auth->logout() );
	}
}
?>