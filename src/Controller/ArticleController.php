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
use App\Model\ArticleModel;
use Cake\Network\Http\Client;
use Cake\Controller\Component\CookieComponent;
use Cake\Core\Configure;

class ArticleController extends AppController {
	//
	//----- helper functions -----
	
	/************************************************************************************
	 * method to login on mediawiki
	 *
	 * @param	string	$mw_user
	 * @param	string	$mw_pass
	 * @return	array	
	 ************************************************************************************/
	protected function mw_login( $mw_user, $mw_pass ) {
		//
		$mw_conf	= Configure::read('mediawiki');
		$http		= new Client( [ 'host' => $mw_conf['url'] . '/' . $mw_conf['apifile'], 'scheme' => $mw_conf['scheme'] ] );
		
		// get login token
		$response	= $http->post( '?action=login&format=json', [ 'lgname' => $mw_user, 'lgpassword' => $mw_pass ] );
		
		if ( !empty( $response->cookies[$mw_conf['cookieprefix'] . '_session']['value'] ) ) {
			// set cookie
			setcookie( $mw_conf['cookieprefix'] . '_session', $response->cookies[$mw_conf['cookieprefix'] . '_session']['value'], time() + 3600 * 24 * 60, "/" );
		}
		
		// try login
		$response	= $http->post( '?action=login&format=json', [ 'lgname' => $mw_user, 'lgpassword' => $mw_pass, 'lgtoken' => json_decode( $response->body )->login->token ] );
		$json		= json_decode( $response->body );
		$cookies	= $response;
		
		// set cookies
		if ( $json->login->result == 'Success' ) {
			// set cookies
			setcookie( $mw_conf['cookieprefix'] . "UserID", $response->cookies[$mw_conf['cookieprefix'] . 'UserID']['value'], time() + 3600 * 24 * 60, "/" );
			setcookie( $mw_conf['cookieprefix'] . "UserName", $response->cookies[$mw_conf['cookieprefix'] . 'UserName']['value'], time() + 3600 * 24 * 60, "/" );
			setcookie( $mw_conf['cookieprefix'] . "Token", $response->cookies[$mw_conf['cookieprefix'] . 'Token']['value'], time() + 3600 * 24 * 60, "/" );
			setcookie( $mw_conf['cookieprefix'] . "LoggedOut", "", time() - 3600, "/" );
		} else {
			// delete cookie
			setcookie( $mw_conf['cookieprefix'] . '_session', $response->cookies[$mw_conf['cookieprefix'] . '_session']['value'], time() - 3600 * 24 * 60, "/" );
		}
		
		return $json;
	}
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	protected function mw_logout() {
		//
		$mw_conf	= Configure::read('mediawiki');
		$http		= new Client( [ 'host' => $mw_conf['url'] . '/' . $mw_conf['apifile'], 'scheme' => $mw_conf['scheme'] ] );
		
		//
		$response	= $http->get( '?action=logout&format=json' );
		
		return json_decode( $response->body );
	}
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	protected function mw_getEditToken() {
		//
		$mw_conf	= Configure::read('mediawiki');
		$http		= new Client( [ 'host' => $mw_conf['url'] . '/' . $mw_conf['apifile'], 'scheme' => $mw_conf['scheme'] ] );
		
		// get edit token
		$response	= $http->get( '?action=query&meta=tokens&type=csrf&rawcontinue&continue&format=json ' );
		
		return json_decode( $response->body );
	}
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	protected function mw_testEditToken( $token ) {
		//
		$mw_conf	= Configure::read('mediawiki');
		$http		= new Client( [ 'host' => $mw_conf['url'] . '/' . $mw_conf['apifile'], 'scheme' => $mw_conf['scheme'] ] );
		
		//
		$response	= $http->get( '?action=checktoken&type=csrf&token=' . urlencode( $token ) . '&format=json' );
		
		return json_decode( $response->body );
	}
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	protected function mw_getArticleByTitle( $title ) {
		//
		$mw_conf	= Configure::read('mediawiki');
		$http		= new Client( [ 'host' => $mw_conf['url'] . '/' . $mw_conf['apifile'], 'scheme' => $mw_conf['scheme'] ] );
		
		//
		$response	= $http->get( '?action=query&prop=revisions&titles=' . $title .'&rvprop=ids|timestamp|content&curtimestamp&rawcontinue&format=json' );
		
		return json_decode( $response->body );
	}
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	protected function mw_getArticleById( $id ) {
		//
		$mw_conf	= Configure::read('mediawiki');
		$http		= new Client( [ 'host' => $mw_conf['url'] . '/' . $mw_conf['apifile'], 'scheme' => $mw_conf['scheme'] ] );
		
		//
		$response	= $http->get( '?action=query&prop=revisions&pageids=' . $id .'&rvprop=ids|timestamp|content&curtimestamp&rawcontinue&format=json' );
		
		return json_decode( $response->body );
	}
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	protected function mw_createArticle(  ) {
		//
		$mw_conf	= Configure::read('mediawiki');
		$http		= new Client( [ 'host' => $mw_conf['url'] . '/' . $mw_conf['apifile'], 'scheme' => $mw_conf['scheme'] ] );
		
		// curl_setopt( $ch, CURLOPT_POSTFIELDS, "title=" . urlencode( $title ) . "&summary=" . urlencode( $summary ) . "&text=" . $text . "&createonly&watchlist=preferences&contentmodel=wikitext&contentformat=text/x-wiki&basetimestamp=" . $curTimestamp . "&token=" . urlencode( $token ) );
	}
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	protected function mw_editArticle( $id = '', $title = '', $text, $summary = '', $editToken, $lastTimestamp  ) {
		//
		if ( empty( $id ) && empty( $title ) ) {
			return false;
		}
		
		//
		if ( isset( $id ) ) {
			$mw_article		= "'pageid' => " . $id;
		} else {
			$mw_aritcle		= "'title'	=> " . $title;
		}
		
		$mw_conf	= Configure::read('mediawiki');
		$http		= new Client( [ 'host' => $mw_conf['url'] . '/' . $mw_conf['apifile'], 'scheme' => $mw_conf['scheme'] ] );
		
		//$curTimestamp	= date( 'Y-m-d' ) . "T" . date( 'H:i:s' ) . "Z";
		
		//
		$response	= $http->post( '?action=edit&format=json',	[
																 'pageid'			=> $id,
																 'summary'			=> urlencode( $summary ),
																 'text'				=> urlencode( $text ),
																 'recreate'			=> '',
																 'watchlist'		=> 'preferences',
																 'contentmodel'		=> 'wikitext',
																 'contentformat'	=> 'text/x-wiki',
																 //'basetimestamp'	=> $lastTimestamp,
																 'token'			=> urlencode( '+\\' ) // urlencode( $editToken )
																],
																[
																 'headers'			=>	[
																 						 'Content-type'	=> 'application/x-www-form-urlencoded'
																 						]
																]);
		//		
		//return $response;
		return json_decode( $response->body );
	}
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function index() {
		// initialise
		$mw_conf	= Configure::read('mediawiki');
		//echo "<br /><br /><br />";
		
		//echo "<pre>";
		/*
		print_r($mw_conf);
		
		/*
		echo '<br />';
		
		print_r($http);
		*/
		/*
		echo '<br />';
		*/
		/*
		print_r( $this->mw_login( $mw_conf['testuser'], $mw_conf['testpass'] ) );
		
		echo '<br />';
		/*
		print_r( $this->mw_logout() );
		
		echo '<br />';
		*/
		/*
		$tmp	= $this->mw_getEditToken();
		
		print_r( $tmp );
		
		echo '<br />';
		
		
		print_r( $this->mw_testEditToken( $tmp->query->tokens->csrftoken ) );
		
		echo '<br />';
		*/
		/*
		print_r( $this->mw_getArticleByTitle( 'Demo:Demosite' ) );
		
		echo "<br />";
		*/
		/*
		
		$bla	= '*';
		
		print_r( $this->mw_getArticleByID( $testingPageId )->query->pages->$testingPageId->revisions[0]->$bla );
		/*
		echo '<br />';
		*/
		/*
		$text		= 'any text is this';
		
		print_r( $this->mw_editArticle( 549, '', $text, 'cakePHP quorra testing', $tmp->query->tokens->csrftoken, ''  ) );
		*/
		//echo "</pre>";
		
		//$this->mw_getEditToken()->query->tokens->csrftoken
		
		//$this->set( 'blub', $huibu['url'] );
		//<?= h($blub); ? >
		
		// workaround with [*] problem
		$testingPageId				= 549;
		$textfield					= '*';
		
		// login with user
		$this->mw_login( $mw_conf['testuser'], $mw_conf['testpass'] );
		
		// get article informations
		$tempArticle				= $this->mw_getArticleByID( $testingPageId );
		
		$formdata['editToken']		= $this->mw_getEditToken()->query->tokens->csrftoken;
		$formdata['curTimestamp']	= $tempArticle->curtimestamp;
		$formdata['articleId']		= $testingPageId;
		$formdata['articleTitle']	= $tempArticle->query->pages->$testingPageId->title;
		$formdata['articleText']	= $tempArticle->query->pages->$testingPageId->revisions[0]->$textfield;
		
		//echo $formdata['articleText'];
		/*
		echo "<pre>";
		print_r( $formdata );
		echo "</pre>";
		*/
		//$formdata['']= $tempArticle;
		$this->set( 'data', $formdata );
	}
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function edit() {
		//
		
		/*
		echo '<br /><br /><br /><pre>';
		print_r( $this->request->data );
		echo '</pre>';
		*/
		
		//
		if ( !empty( $this->request->data ) ) {
			//
			if ( !empty( $this->request->data['mw_articleTitle'] ) && !empty( $this->request->data['mw_articleContent'] ) ) {
				//
				$token		= $this->mw_getEditToken()->query->tokens->csrftoken;
				$result		= $this->mw_editArticle( $this->request->data['mw_articleId'], $this->request->data['mw_articleTitle'], $this->request->data['mw_articleContent'], 'cakePHP quorra testing', $token, $this->request->data['mw_curTimestamp'] );
				
				switch( $result->error->code ) {
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
					case '':
						$this->set( 'result', '' );
						break;
					*/
					default:
						$this->set( 'result', 'Scheinbar alles ok?!' );
						break;
				}
				
				/*
				echo '<br /><br /><br /><pre>';
				print_r( $result );
				echo '</pre>';
				*/
				//$this->set( 'result', 'Spannung!' );
			}
		} else {
			$this->set( 'result', 'Es wurden keine Daten übergeben.' );
		}
		
		//$this->set( 'data', $data );
	}
	
	
	
}
?>