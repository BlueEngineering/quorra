<?php
/************************************************************************************
 * mediawiki API component for CakePHP
 *
 * @author	Tim Jaap	<tim.jaap@mailbox.tu-berlin.de>
 * @version	1.0
 ************************************************************************************/
//
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Network\Http\Client;
use Cake\Controller\Component\CookieComponent;
use Cake\Core\Configure;

class MediawikiAPIComponent extends Component {
	/************************************************************************************
	 * login on mediawiki
	 *
	 * @param	string	$mw_user, username of mediawiki account
	 * @param	string	$mw_pass, password of mediawiki account
	 * @return	json array
	 ************************************************************************************/
	public function mw_login( $mw_user, $mw_pass ) {
		//
		$mw_conf	= Configure::read('quorra');
		$http		= new Client( [ 'host' => $mw_conf['mediawiki']['url'] . '/' . $mw_conf['mediawiki']['apifile'], 'scheme' => $mw_conf['mediawiki']['scheme'] ] );
		
		// get login token
		$response	= $http->post( '?action=login&format=json', [ 'lgname' => $mw_user, 'lgpassword' => $mw_pass ] );
		
		if ( !empty( $response->cookies[$mw_conf['mediawiki']['cookieprefix'] . '_session']['value'] ) ) {
			// set cookie
			setcookie( $mw_conf['mediawiki']['cookieprefix'] . '_session', $response->cookies[$mw_conf['mediawiki']['cookieprefix'] . '_session']['value'], time() + 3600 * 24 * 60, "/" );
		}
		
		// try login
		$response	= $http->post( '?action=login&format=json', [ 'lgname' => $mw_user, 'lgpassword' => $mw_pass, 'lgtoken' => json_decode( $response->body )->login->token ] );
		$json		= json_decode( $response->body );
		$cookies	= $response;
		
		// set cookies
		if ( $json->login->result == 'Success' ) {
			// set cookies
			setcookie( $mw_conf['mediawiki']['cookieprefix'] . "UserID", $response->cookies[$mw_conf['mediawiki']['cookieprefix'] . 'UserID']['value'], time() + 3600 * 24 * 60, "/" );
			setcookie( $mw_conf['mediawiki']['cookieprefix'] . "UserName", $response->cookies[$mw_conf['mediawiki']['cookieprefix'] . 'UserName']['value'], time() + 3600 * 24 * 60, "/" );
			setcookie( $mw_conf['mediawiki']['cookieprefix'] . "Token", $response->cookies[$mw_conf['mediawiki']['cookieprefix'] . 'Token']['value'], time() + 3600 * 24 * 60, "/" );
			setcookie( $mw_conf['mediawiki']['cookieprefix'] . "LoggedOut", "", time() - 3600, "/" );
		} else {
			// delete cookie
			setcookie( $mw_conf['mediawiki']['cookieprefix'] . '_session', $response->cookies[$mw_conf['mediawiki']['cookieprefix'] . '_session']['value'], time() - 3600 * 24 * 60, "/" );
		}
		
		return $json;
	}
	
	/************************************************************************************
	 * logout from mediawiki
	 *
	 * @return	json array
	 ************************************************************************************/
	public function mw_logout() {
		//
		$mw_conf	= Configure::read('quorra');
		$http		= new Client( [ 'host' => $mw_conf['mediawiki']['url'] . '/' . $mw_conf['mediawiki']['apifile'], 'scheme' => $mw_conf['mediawiki']['scheme'] ] );
		
		//
		$response	= $http->get( '?action=logout&format=json' );
		
		return json_decode( $response->body );
	}
	
	/************************************************************************************
	 * get an edit token to edit articles, userrights or something how required an token.
	 *
	 * @return json array
	 ************************************************************************************/
	public function mw_getEditToken() {
		//
		$mw_conf	= Configure::read('quorra');
		$http		= new Client( [ 'host' => $mw_conf['mediawiki']['url'] . '/' . $mw_conf['mediawiki']['apifile'], 'scheme' => $mw_conf['mediawiki']['scheme'] ] );
		
		// get edit token
		$response	= $http->get( '?action=query&meta=tokens&type=csrf&rawcontinue&continue&format=json ' );
		
		return json_decode( $response->body );
	}
	
	/************************************************************************************
	 * test the from mediawiki received edit token.
	 *
	 * @param	string	$token, the received edit token.
	 * @return	json array
	 ************************************************************************************/
	public function mw_testEditToken( $token ) {
		//
		$mw_conf	= Configure::read('quorra');
		$http		= new Client( [ 'host' => $mw_conf['mediawiki']['url'] . '/' . $mw_conf['mediawiki']['apifile'], 'scheme' => $mw_conf['mediawiki']['scheme'] ] );
		
		//
		$response	= $http->get( '?action=checktoken&type=csrf&token=' . urlencode( $token ) . '&format=json' );
		
		return json_decode( $response->body );
	}
	
	/************************************************************************************
	 * parsing given text from mediawiki syntax into html
	 *
	 * @param	string	$text, given text where parse from mediawiki syntax into html
	 * @return	string	$text
	 ************************************************************************************/
	public function mw_parsingWikisyntaxToHtml( $text ) {
		// loading mediawiki config file
		$mw_conf	= Configure::read('quorra');
		
		// transfer bold text
		$text		= preg_replace( "/'''(.*)'''/Usi", "<strong>$1</strong>", $text );
		
		// transfer italic text
		$text		= preg_replace( "/''(.*)''/Usi", "<em>$1</em>", $text );
		
		// transfer headlines
		$text		= preg_replace( "/======(.*)======/Usi", "<h6>$1</h6>", $text );
		$text		= preg_replace( "/=====(.*)=====/Usi", "<h5>$1</h5>", $text );
		$text		= preg_replace( "/====(.*)====/Usi", "<h4>$1</h4>", $text );
		$text		= preg_replace( "/===(.*)===/Usi", "<h3>$1</h3>", $text );
		$text		= preg_replace( "/==(.*)==/Usi", "<h2>$1</h2>", $text );
		$text		= preg_replace( "/= (.*) =/Usi", "<h1>$1</h1>", $text );
		
		// transfer ordered list
		//$text		= preg_replace( "/\#.(.*)[\s]/Usi", "<oli>$1</oli>", $text );
		
		// transfer unordered list
		/*
		$text		= preg_replace_callback( "/\*.(.*)[\s]/Usi", function ( $text ) {
						$list	= "";
						
						// iterate over all list elements
						foreach( $text as $lst ) {
							$list	= $list .  '<li>' . trim( $lst ) . '</li>';
						}
						
						return "<ul>" . $list . "</ul>";
						}, $text );*/
		
		// transfer internal weblinks
		$text		= preg_replace_callback( "/\[\[(.*)\]\]/Usi", function ( $text ) {
						$mw_conf	= Configure::read('quorra');
						
						// split weblink in url and customized linktext
						$split		= explode( "|", $text[1] );
						
						// have weblink an customized linktext?
						if ( count( $split ) > 1 ) {
							// internal weblink with customized linktext
							return '<a href="http://' . $mw_conf['mediawiki']['url'] . '/index.php/' . trim( $split[0] ) . '">'. trim( $split[1] ) . '</a>';
						} else {
							// internal weblink without customized linktext
							return '<a href="http://' . $mw_conf['mediawiki']['url'] . '/index.php/' . trim( $split[0] ) . '">'. trim( $split[0] ) . '</a>';
						} }, $text );
		
		// transfer external weblinks
		$text		= preg_replace_callback( "/\[(.*)\]/Usi", function ( $text ) {
						$split		= explode( " ", $text[1] );
						
						if( count( $split ) > 1 ) {
							$urltxt			= "";
							
							for ( $i = 1; $i < count( $split ); $i++ ) {
								$urltxt		.= $split[ $i ];
								if ( $i != count( $split ) - 1) {
									$urltxt	.= " ";
								}
							}
							
							return '<a href="' . trim( $split[0] ) . '" target="_blank">' . $urltxt . '</a>';
						} else {
							return '<a href="' . trim( $split[0] ) . '" target="_blank">' . $split[0] . '</a>';
						} }, $text );
		
		// transfer urls as weblinks
		//$text		= preg_replace( "/(http:\/\/(.*)|https:\/\/|ftp:\/\/|mailto:|news:\/\/|gopher:\/\/|irc:\/\/)/Usi", '<a href="$0" target="_blank">$0</a>', $text );
		//$text = preg_replace('#(( |^)((ftp|http|https)://)\S+)#mi', "<a href=\"$1\" target=\"_blank\">$1</a>", $text);
				
		// transfer referances
		/*
		Bsp.
		Quorra<sup id="cite_ref-quorra_1-0" class="reference"><a href="#cite_note-quorra-1">[1]</a></sup>
		
		<ol class="references">
		<li id="cite_note-quorra-1"><span class="mw-cite-backlink"><a href="#cite_ref-quorra_1-0">↑</a></span> <span class="reference-text">Der Name <a rel="nofollow" class="external text" href="https://de.tronlegacy.wikia.com/wiki/Quorra">Quorra</a> bezieht sich auf die gleichnamige Figure aus dem Film <a rel="nofollow" class="external text" href="https://de.wikipedia.org/wiki/Tron:_Legacy">TRON:Legacy</a>.</span></li>
		</ol>
		*/
		
		// filtering magic words
		//$text	= preg_replace( "/[\_\_NOTOC\_\_]/Usi", "", $text );
		//$text	= strtr( $text, "__NOTOC__", "" );
		
		return $text;
	}
	
	/************************************************************************************
	 * parsing given text from html into mediawiki syntax
	 *
	 * @param	string	$text, given text where parse from html into mediawiki syntax
	 * @return	string	$text
	 ************************************************************************************/
	public function mw_parsingHtmlToWikisyntax( $text ) {
		// transfer bold text
		$text		= preg_replace( "/<b>(.*)<\/b>/Usi", "'''$1'''", $text );
		$text		= preg_replace( "/<strong>(.*)<\/strong>/Usi", "'''$1'''", $text );
		
		// transfer italic text
		$text		= preg_replace( "/<i>(.*)<\/i>/Usi", "'''$1'''", $text );
		$text		= preg_replace( "/<em>(.*)<\/em>/Usi", "'''$1'''", $text );
		
		// transfer headlines
		$text		= preg_replace( "/<h1>(.*)<\/h1>/Usi", "= $1 =", $text );
		$text		= preg_replace( "/<h2>(.*)<\/h2>/Usi", "== $1 ==", $text );
		$text		= preg_replace( "/<h3>(.*)<\/h3>/Usi", "=== $1 ===", $text );
		$text		= preg_replace( "/<h4>(.*)<\/h4>/Usi", "==== $1 ====", $text );
		$text		= preg_replace( "/<h5>(.*)<\/h5>/Usi", "===== $1 =====", $text );
		$text		= preg_replace( "/<h6>(.*)<\/h6>/Usi", "====== $1 ======", $text );
		
		// transfer ordered list
		//$text		= preg_replace( "/<ol>(.*)<\/ol>/Usi", "\\1", $text );
		//$text		= preg_replace( "/<li>(.*)<\/li>/Usi", "*\\1", $text );
		//$text		= preg_replace( "/<ol>(.*)<\/ol>/Usi", "\\1", preg_replace( "/<li>(.*)<\/li>/Usi", "*\\1", $text ) );
		//$temp		= preg_replace( "/<ol>(.*)<\/ol>/Usi", "\\1", $text );
		//$text		= preg_replace( "/<li>(.*)<\/li>/Usi", "*\\1", $temp );
		
		// transfer unordered list
		
		// transfer weblinks
		$text		= preg_replace_callback( "/<a(.*)href=\"(.*)\">(.*)<\/a>/Usi", function ( $text ) {
						$mw_conf	= Configure::read('quorra');
						
						// delete all HTML or PHP tags
						$linktext	= strip_tags( $text[0] );
												
						// string beginns with http, https, ftp, mailto, news or irc => weblink without linktext
						if ( stripos( $linktext, "http://" ) !== false || stripos( $linktext, "https://" ) !== false || stripos( $linktext, "ftp://" ) !== false || stripos( $linktext, "mailto:" ) !== false || stripos( $linktext, "news://" ) !== false || stripos( $linktext, "irc://" ) !== false ) {
							return '[' . $linktext . ']';
						}
						
						// search HTML weblink attribute href in string
						$urlIndexStart	= strpos( $text[0], 'href="' );
												
						// check is href attribute set
						if ( $urlIndexStart !== false ) {
							// is target attribute set? in both cases is offset 6 ( href=" are 6 characters)
							if ( strpos( $text[0], 'target="' ) !== false ) {
								// url with target attribute
								$urlIndexEnd	= strpos( $text[0], '" ', + ( $urlIndexStart + 6 ) );
							} else {
								// url without target attribute
								$urlIndexEnd	= strpos( $text[0], '">', + ( $urlIndexStart + 6 ) );
							}
							
							// get url
							$url			= substr( $text[0], $urlIndexStart + 6, ( $urlIndexEnd - 9 ) );
							
							// is link an external link?
							if ( strpos( $url, "http://" ) !== false || strpos( $url, "https://" ) !== false || strpos( $url, "ftp://" ) !== false || strpos( $url, "mailto://" ) !== false || strpos( $url, "news://" ) !== false || strpos( $url, "irc://" ) !== false ) {
								// build a external mediawiki link
								return '[' . $url . ' ' . $linktext . ']';
							} else {
								// search start position of mediawiki article name
								$sitetitleIndexStart	= strpos( $url, 'index.php/' );
								
								// get mediawiki article name
								$sitetitle				= substr( $url, $sitetitleIndexStart + 10 );
								
								// is this link a internal weblink with or without customize text?
								if ( $sitetitle === $linktext ) {
									// link without customized text
									return '[[' . $sitetitle . ']]';
								} else {
									// link with customized text
									return '[[' . $sitetitle . ' | ' . $linktext . ']]';
								}
							}
						}
						
						// otherwise case
						$txt 		= "";
						
						return $txt;
						}, $text );
		
		// transfer referances
		
		return $text;
	}
	
	/************************************************************************************
	 * filtering mediawiki magic words from text
	 *
	 * @param	string	$text
	 * @return	json array
	 ************************************************************************************/
	public function mw_parseMagicWords( $text ) {
		$magicWords		= array();
		
		// ----- behavior switches -----
		// is no table of content set?
		if ( preg_match_all( "/[__NOTOC__]/Usi", $text ) > 0 ) {
			$magicWords['BehaviorSwitches']['NOTOC']	= "1";
		} else {
			$magicWords['BehaviorSwitches']['NOTOC']	= "0";
		}
		
		// is don't show edit section links set?
		if ( preg_match_all( "/[__NOEDITSECTION__]/Usi", $text ) > 0 ) {
			$magicWords['BehaviorSwitches']['NOEDITSECTION']	= "1";
		} else {
			$magicWords['BehaviorSwitches']['NOEDITSECTION']	= "0";
		}
		
		// is hide categories set?
		if ( preg_match_all( "/[__HIDDENCAT__]/Usi", $text ) > 0 ) {
			$magicWords['BehaviorSwitches']['HIDDENCAT']	= "1";
		} else {
			$magicWords['BehaviorSwitches']['HIDDENCAT']	= "0";
		}
		
		return $magicWords;
	}
	
	/************************************************************************************
	 * get article informations from mediawiki by article title
	 *
	 * @param	string	$title, title of mediawiki article
	 * @return	json array
	 ************************************************************************************/
	public function mw_getArticleByTitle( $title ) {
		//
		$mw_conf	= Configure::read('quorra');
		$http		= new Client( [ 'host' => $mw_conf['mediawiki']['url'] . '/' . $mw_conf['mediawiki']['apifile'], 'scheme' => $mw_conf['mediawiki']['scheme'] ] );
		
		//
		$response	= $http->get( '?action=query&prop=revisions&titles=' . $title .'&rvprop=ids|timestamp|content&curtimestamp&rawcontinue&format=json' );
		
		return json_decode( $response->body );
	}
	
	/************************************************************************************
	 * get article informations form mediawiki by article id
	 *
	 * @param	int		$id, id of mediawiki article
	 * @return	json array
	 ************************************************************************************/
	public function mw_getArticleById( $id ) {
		//
		$mw_conf	= Configure::read('quorra');
		$http		= new Client( [ 'host' => $mw_conf['mediawiki']['url'] . '/' . $mw_conf['mediawiki']['apifile'], 'scheme' => $mw_conf['mediawiki']['scheme'] ] );
		
		//
		$response	= $http->get( '?action=query&prop=revisions&pageids=' . $id .'&rvprop=ids|timestamp|content&curtimestamp&rawcontinue&format=json' );
		
		return json_decode( $response->body );
	}
	
	/************************************************************************************
	 * create a new mediawiki article if their don't exists.
	 *
	 * @param	string	$title		title of new mediawiki article
	 * @param	string	$text		text content of new mediawiki article
	 * @param	string	$summary	a summary where descripte edits in few words.
	 * @param	string	$editToken	edit token. required by mediawiki
	 * @return	json	array
	 ************************************************************************************/
	public function mw_createArticle( $title, $text, $summary = '', $editToken ) {
		// is $title or $text doesn't exist return false
		if ( empty( $title ) || empty( $text ) ) {
			return false;
		}
		
		// load quorra config
		$mw_conf	= Configure::read('quorra');
		
		// create connection to mediawiki
		$http		= new Client( [ 'host' => $mw_conf['mediawiki']['url'] . '/' . $mw_conf['mediawiki']['apifile'], 'scheme' => $mw_conf['mediawiki']['scheme'] ] );
		
		// execute
		$response	= $http->post( '?action=edit&format=json',	[
																 'title'			=> trim( $title ),
																 //'summary'			=> urlencode( $summary ),
																 'summary'			=> trim( $summary ),
																 //'text'				=> urlencode( $text ),
																 'text'				=> $text,
																 'createonly'		=> '',
																 'watchlist'		=> 'preferences',
																 'contentmodel'		=> 'wikitext',
																 'contentformat'	=> 'text/x-wiki',
																 //'basetimestamp'	=> $lastTimestamp,
																 'token'			=> $editToken //urlencode( '+\\' ) // urlencode( $editToken )
																],
																[
																 'headers'			=>	[
																 						 'Content-type'	=> 'application/x-www-form-urlencoded'
																 						]
																] );
		// return mediawiki api answer
		return json_decode( $response->body );
		
		// curl_setopt( $ch, CURLOPT_POSTFIELDS, "title=" . urlencode( $title ) . "&summary=" . urlencode( $summary ) . "&text=" . $text . "&createonly&watchlist=preferences&contentmodel=wikitext&contentformat=text/x-wiki&basetimestamp=" . $curTimestamp . "&token=" . urlencode( $token ) );
	}
	
	/************************************************************************************
	 * edit an existing mediawiki article
	 *
	 * @param	int		$id			id of mediawiki article (required if title is not set)
	 * @param	string	$title		title of mediawiki article (required if id is not set)
	 * @param	string	$text		text of mediawiki article who will edit.
	 * @param	string	$summary	a summary where descripte edits in few words.
	 * @param	string	$editToken	edit token. required by mediawiki
	 * @param	string	$lastTimestamp	last received timestamp of last article changes 
	 * @return	json	array
	 ************************************************************************************/
	public function mw_editArticle( $id = '', $title = '', $text, $summary = '', $editToken, $lastTimestamp  ) {
		// $id and $title doesn't exist return false
		if ( empty( $id ) && empty( $title ) ) {
			return false;
		}
		
		// is $id set use this else use $title
		if ( isset( $id ) ) {
			$mw_article		= "'pageid' => " . $id;
		} else {
			$mw_aritcle		= "'title'	=> " . $title;
		}
		
		// load quorra config
		$mw_conf	= Configure::read('quorra');
		
		// create connection to mediawiki api
		$http		= new Client( [ 'host' => $mw_conf['mediawiki']['url'] . '/' . $mw_conf['mediawiki']['apifile'], 'scheme' => $mw_conf['mediawiki']['scheme'] ] );
		
		// create current timestamp for finding edit conflicts
		//$curTimestamp	= date( 'Y-m-d' ) . "T" . date( 'H:i:s' ) . "Z";
		
		// execute api call
		$response	= $http->post( '?action=edit&format=json',	[
																 'pageid'			=> $id,
																 //'summary'			=> urlencode( $summary ),
																 'summary'			=> $summary,
																 //'text'				=> urlencode( $text ),
																 'text'				=> $text,
																 'recreate'			=> '',
																 'watchlist'		=> 'preferences',
																 'contentmodel'		=> 'wikitext',
																 'contentformat'	=> 'text/x-wiki',
																 //'basetimestamp'	=> $lastTimestamp,
																 'token'			=> $editToken //urlencode( '+\\' ) // urlencode( $editToken )
																],
																[
																 'headers'			=>	[
																 						 'Content-type'	=> 'application/x-www-form-urlencoded'
																 						]
																]);
		// return mediawiki api answer
		return json_decode( $response->body );
	}
}
?>