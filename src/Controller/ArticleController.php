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
		
		// try login
		$response	= $http->post( '?action=login&format=json', [ 'lgname' => $mw_user, 'lgpassword' => $mw_pass, 'lgtoken' => json_decode( $response->body )->login->token ] );
		
		
		return json_decode( $response->body );
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
	protected function mw_editArticle( $id = '', $title = '', $text, $summary = '', $editToken, $lastRevId, $lastTimestamp  ) {
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
		return $response;
		return json_decode( $response->body );
	}
	
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function index() {
		// initialise
		$mw_conf	= Configure::read('mediawiki');
		/*
		$http		= new Client( [ 'host'		=> $mw_conf['url'] . '/' . $mw_conf['apifile'],
									'port'		=> 80,
									'scheme'	=> 'http',
									'ssl_verify_peer' => false,
									'ssl_verify_host' => true
								] );
		*/
		
		//$abc		= new ArticleModel();
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
		
		print_r( $this->mw_editArticle( 549, '', $text, 'cakePHP quorra testing', $tmp->query->tokens->csrftoken, '', ''  ) );
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
	public function edit( $id = null ) {
		//		
	}
	
	
	
}
?>