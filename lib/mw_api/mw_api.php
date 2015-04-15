<?PHP
/************************************************************************************
 * Controller class: Interface to mediaWiki API.
 *
 * @author		Tim Jaap <tim.jaap@mailbox.tu-berlin.de>
 * @version		0.1 (beta)
 ************************************************************************************/
//
class mw_api {
	// attributes
	private	$mwAPI_url;				// URL to mediawiki
	private $mwAPI_cookieprefix;	// Prefix of Cookies
	
	// constructor
	public function __construct( $url, $cookieprefix ) {
		$this->mwAPI_url			= $url;
		$this->mwAPI_cookieprefix	= $cookieprefix;
	}
	
	// methods
	/************************************************************************************
	 * getter method to get URL attribute
	 *
	 * @return	String	$this->mwAPI_url
	 ************************************************************************************/
	public function mw_api_getUrl() {
		return $this->mwAPI_url;
	}
	
	/************************************************************************************
	 * getter method to get cookieprefix attribute
	 *
	 * @return	String	$this->mwAPI_cookieprefix;
	 ************************************************************************************/
	public function mw_api_getCookieprefix() {
		return $this->mwAPI_cookieprefix;
	}
	
	/************************************************************************************
	 * method to log in mediawiki system.
	 *
	 * @param	User	$user
	 * @return	Array	( Boolean $status, Int $err_code, String $token)
	 ************************************************************************************/
	public function mw_api_login( $username, $userpass ) {
		// check if user is already logged
		if( $this->mw_api_isLogged()[0] == true ) {
			return array( true, 10, $_COOKIE[ $this->mwAPI_cookieprefix . "Token" ] );
		}
		
		// ----- initial process to get login token -----
		// initial cURL session
		$ch					= curl_init( $this->mwAPI_url );
		
		// set cURL options
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, "action=login&lgname=" . $username . "&lgpassword=" . $userpass . "&format=xml" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		
		// execute cURL process
		$curl_token_res		= simplexml_load_string( curl_exec( $ch ) );
		
		// create cookies with 60 days lifetime
		setcookie( $this->mwAPI_cookieprefix . "UserName", $username, time() + 3600 * 24 * 60, "/" );
		setcookie( $this->mwAPI_cookieprefix . "_session", $curl_token_res->login->attributes()->sessionid, time() + 3600 * 24 * 60, "/" );
		
		// ----- initial cURL process to log in on mw -----
		// set cURL options
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_COOKIE, $this->mwAPI_cookieprefix . "_session=" . $curl_token_res->login->attributes()->sessionid );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, "action=login&lgname=" . $username . "&lgpassword=" . $userpass . "&lgtoken=" . $curl_token_res->login->attributes()->token . "&format=xml" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		
		// start login process
		$curl_login_res		= simplexml_load_string( curl_exec( $ch ) );
		
		// check result of login process
		if( $curl_login_res->login->attributes()->result == "Success" ) {
			// create UserID and token cookies and delete logout cookie
			setcookie( $this->mwAPI_cookieprefix . "UserID", $curl_login_res->login->attributes()->lguserid, time() + 3600 * 24 * 60, "/" );
			setcookie( $this->mwAPI_cookieprefix . "Token", $curl_token_res->login->attributes()->token, time() + 3600 * 24 * 60, "/" );
			setcookie( $this->mwAPI_cookieprefix . "LoggedOut", "", time() - 3600, "/" );
		} else {
			// delete created cookies UserName, _session, Token and UserID if login is failed
			setcookie( $this->mwAPI_cookieprefix . "UserName", "", time() - 3600, "/" );
			setcookie( $this->mwAPI_cookieprefix . "_session", "", time() - 3600, "/" );
			setcookie( $this->mwAPI_cookieprefix . "UserID", "", time() - 3600, "/" );
			setcookie( $this->mwAPI_cookieprefix . "Token", "", time() - 3600, "/" );
		}
		
		// close cURL session
		curl_close( $ch );
		
		// return values
		switch( $curl_login_res->login->attributes()->result ) {
			// login successful
			case "Success" : 
				return array( true, 0, $curl_token_res->login->attributes()->token );
			
			// error: lost token
			case "NeedToken" :
				return array( false, 9, "" );
				
			// error: no username
			case "NoName" :
				return array( false, 1, "" );
			
			// error: illegal username
			case "Illegal" :
				return array( false, 2, "" );
			
			// error: username is not exists
			case "NotExists" :
				return array( false, 3, "" );
			
			// error: user given password
			case "EmptyPass" :
				return array( false, 4, "" );
			
			// error: password is wrong
			case "WrongPass" :
				return array( false, 5, "" );
			
			// error: an plugin return wrong password
			case "WrongPluginPass" :
				return array( false, 6, "" ); 
				
			// error: can not create an user cause ip is blocked
			case "CreateBlocked" :
				return array( false, 7, "" );
				
			// error: too many failed login tries
			case "Throttled" :
				return array( false, 8, "" );
				
			// error: unknown error
			default :
				return array( false, 11, "" );
		}
	}
	
	/************************************************************************************
	 * method to check if user already logged.
	 *
	 * @return	Array	( Boolean $status, Int $err_code )
	 ************************************************************************************/
	public function mw_api_isLogged() {
		// Exists the typical cookies?
		if( isset( $_COOKIE[$this->mwAPI_cookieprefix ."UserID"] ) && isset( $_COOKIE[$this->mwAPI_cookieprefix ."UserName"] ) && isset( $_COOKIE[$this->mwAPI_cookieprefix ."_session"] ) ) {
			return array( true, 0 );
		}
		
		return array( false, 1 );
	}
	
	/************************************************************************************
	 * method to log out on mediawiki system.
	 *
	 * @return	Array	( Boolean $status, Int $err_code )
	 ************************************************************************************/
	public function mw_api_logout() {
		// ----- mw logout procedure -----
		// initial cURL session
		$ch			= curl_init( $this->mwAPI_url . '?action=logout' );
		
		// set cURL options
		curl_setopt( $ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		//curl_setopt( $ch, CURLOPT_MUTE, true );
		
		// start logout process
		curl_exec( $ch );
		
		// Delete cookies
		setcookie( $this->mwAPI_cookieprefix . "UserName", "", time() - 3600, "/" );
		setcookie( $this->mwAPI_cookieprefix . "_session", "", time() - 3600, "/" );
		setcookie( $this->mwAPI_cookieprefix . "UserID", "", time() - 3600, "/" );
		setcookie( $this->mwAPI_cookieprefix . "Token", "", time() - 3600, "/" );
		
		// close cURL session
		curl_close( $ch );
	}
	
	/************************************************************************************
	 * method to get an edit token from mediawiki system
	 *
	 * @return	Array	( String $csrftoken, String $patroltoken, String $rollbacktoken, String $userrightstoken, String $watchtoken )
	 ************************************************************************************/
	public function mw_api_getEditToken() {
		// initial cURL session
		$ch			= curl_init( $this->mwAPI_url . "?action=query&meta=tokens&type=csrf|patrol|rollback|userrights|watch&rawcontinue&continue&format=json" );
		
		// set cURL options
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		
		// start cURL process to get an edit token from mediawiki system
		//curl_exec( $ch );
		$curl_res	= curl_exec( $ch );
		
		// close cURL session
		curl_close( $ch );
				
		// return with json_decode() from $curl_res extrakted array with tokens
		return json_decode( $curl_res, true )["query"]["tokens"];
	}
	 
	/************************************************************************************
	 * method to test an edit token from mediawiki system
	 * 
	 ************************************************************************************/
	public function mw_api_testEditToken( $token ) {
		// initial a cURL session
		$ch			= curl_init( $this->mwAPI_url . "?action=checktoken&type=csrf&token=" . $token . "&format=json" );
		
		// set cURL options
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		
		// start cURL process
		$curl_res	= curl_exec( $ch );
		
		// close cURL session
		curl_close( $ch );
		
		// check if error returned
		if( isset( json_decode( $curl_res, true )["error"] ) && json_decode( $curl_res, true )["error"]["code"] == "notoken" ) {
			return array( false, 1 );
		}
		
		// return values
		switch( json_decode( $curl_res, true )["checktoken"]["result"] ) {
			case "valid":
				return array( true, 0 );				
			case "invalid":
				return array( false, 2 );	
		}
	}
	
	/************************************************************************************
	 * method to check if page is exist
	 *
	 * @param	String	$title
	 * @return	Boolean	$pageStatus
	 ************************************************************************************/
	public function mw_api_isPageExist( $title ) {
		// initial a cURL session
		$ch			= curl_init( $this->mwAPI_url . "?action=query&prop=info&indexpageids&titles=" . $title . "&rawcontinue&format=json" );
		
		// set cURL options
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		
		// start cURL process
		$curl_res	= curl_exec( $ch );
		
		// close cURL session
		curl_close( $ch );
		
		// exist page?
		if( json_decode( $curl_res, true )["query"]["pageids"][0] > -1 ) {
			return array( true, json_decode( $curl_res, true )["query"]["pageids"][0] );
		}
		
		// otherwise case
		return array( false, json_decode( $curl_res, true )["query"]["pageids"][0] );
	}
	
	/************************************************************************************
	 * method to get mediawiki article content.
	 *
	 * @param	Int		$pageid
	 * @return	Array	( int $revid, int $parentid, String $timestamp, String $contentformat, String $contentmodel, String $* )
	 ************************************************************************************/
	public function mw_api_getPageContent( $pageid ) {
		// initial a cURL session
		$ch			= curl_init( $this->mwAPI_url . "?action=query&prop=revisions&pageids=" . $pageid ."&rvprop=ids|timestamp|content&rawcontinue&format=json" );
		
		// set cURL options
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		
		// start cURL process
		$curl_res	= curl_exec( $ch );
		
		// error case
		if ( isset( json_decode( $curl_res, true )["query"]["pages"][$pageid]["missing"] ) ) {
			return array( "revid" => -1, "parentid" => -1, "timestamp" => "", "contentformat" => "", "contentmodel" => "", "*" => "" );
		}
		
		// successful return
		return json_decode( $curl_res, true )["query"]["pages"][$pageid]["revisions"][0];
	}
	
	/************************************************************************************
	 * method to get last revision id of given article.
	 *
	 * @param	int		$pageid
	 * @return	array	(int $revid, int $parentid, String $timestamp )
	 ************************************************************************************/
	public function mw_api_getLastRevId( $pageid ) {
		// initial a cURL session
		$ch			= curl_init( $this->mwAPI_url . "?action=query&prop=revisions&pageids=" . $pageid ."&rvprop=ids|timestamp&rawcontinue&format=json" );
		
		// set cURL options
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		
		// start cURL process
		$curl_res	= curl_exec( $ch );
		
		// error case
		if ( isset( json_decode( $curl_res, true )["query"]["pages"][$pageid]["missing"] ) ) {
			return array( "revid" => -1, "parentid" => -1, "timestamp" => "" );
		}
		
		// successful case
		return json_decode( $curl_res, true )["query"]["pages"][$pageid]["revisions"][0];
	}
	
	/************************************************************************************
	 * method to create/edit a mediawiki article. http://www.mediawiki.org/wiki/API:Edit
	 *
	 * @param	Int		$pageid
	 * @param	String	$title
	 * @param	String	$text
	 * @param	String	$summary (optional)
	 * @param	String	$token
	 * @return	Array	( Boolean $status, Int $err_code )
	 ************************************************************************************/
	public function mw_api_editPage( $pageid, $title = "", $text, $summary = "", $token, $lastRevId, $lastTimestamp ) {
		// check if user already logged on mediawiki system.
		if( $this->mw_api_isLogged() == false ) {
			return array( false, 1 );
		}
		
		// initial cURL session
		$ch			= curl_init( $this->mwAPI_url );
		
		// set cURL options
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-type: application/x-www-form-urlencoded' ) );
		curl_setopt( $ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] ); 
		//curl_setopt( $ch, 
		
		// new page or edit existed page? pageid = -1 => new page case 
		if ( $pageid == -1 ) {
			// new page case
			
			// isn't set title?
			if ( !isset( $title ) ) {
				return array( false, 2 );
			}
			
			// isn't set text?
			if ( !isset( $text ) ) {
				return array( false, 3 );
			}
			
			// create current timestamp
			$curTimestamp	= date( 'Y-m-d' ) . "T" . date( 'H:i:s' ) . "Z";
			
			// set POST data @end urlencode( "+\\" ) ? add md5&
			curl_setopt( $ch, CURLOPT_POSTFIELDS, "action=edit&title=" . $title . "&summary=" . $summary . "&text=" . $text . "&watchlist=preferences&contentmodel=wikitext&contentformat=text/x-wiki&format=json&basetimestamp=" . $curTimestamp . "&token=" . urlencode( $token ) );
			
		} else {
			// edit page case
		}
		
		// start cURL process to create/edit a page
		$curl_res	= curl_exec( $ch );
		
		// close cURL session
		curl_close( $ch );
		
		return json_decode( $curl_res, true );
		
		/*
		mw_api_getLastRevId( $pageid )["revid"]
		mw_api_getLastRevId( $pageid )["timestamp"]
		*/
		
		
		// parse given text to mediawiki code
		// mw_api_parseToWikiCode( $text );
		
		// initial a cURL session
		/*
		$ch		= curl_init( $this->mwAPI_url );
		
		// set cURL options
		curl_setopt( $ch, CURLOPT_POST, true );
		//curl_setopt( $ch, CURLOPT_URL, $this->mwAPI_url );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, "action=edit&title=" . $title . "&section=new&summary=" . $summary . "&text=" . $text . "&watch&token=" . $token . urlencode( "+\\" ) );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		
		// start cURL process to create/edit a mediawiki page.
		curl_exec( $ch );
		
		// close cURL session
		curl_close( $ch );
		*/
		// Before send, check if edit revision the current revision
		/*
		action=edit
		&title=$title
		&text=$text
		&summary=$summary
		&basetimestamp=
		&starttimestamp=
		&md5=
		&token=$_AusUserSessionEntnehmen
		*/
	}
	 
	/************************************************************************************
	 * method to move a mediawiki article.
	 * 
	 ************************************************************************************/
	public function mw_api_movePage() {
		//
		return;
	}
	
	/************************************************************************************
	 * method to delete a mediawiki article.
	 * 
	 ************************************************************************************/
	public function mw_api_delPage() {
		//
		return;
	}
	
	/************************************************************************************
	 * method to create a mediawiki user.
	 * 
	 ************************************************************************************/
	public function mw_api_addUser() {
		//
		return;
	}
	
	/************************************************************************************
	 * method to delete a mediawiki user.
	 * 
	 ************************************************************************************/
	public function mw_api_delUser() {
		//
		return;
	}
	
	/************************************************************************************
	 * method to lock a mediawiki user.
	 * 
	 ************************************************************************************/
	public function mw_api_lockUser() {
		//
		return;
	}
	 
	/************************************************************************************
	 * method to parse HTML code in wikimedia code 
	 * 
	 ************************************************************************************/
	public function mw_api_parseToWikiCode() {
		//
		return;
	}
	 
	/************************************************************************************
	 * method to parse wikimedia code to HTML code
	 * 
	 ************************************************************************************/
	public function mw_api_parseToHTML() {
		//
		return;
	}
	 
	/************************************************************************************
	 * 
	 * 
	 ************************************************************************************/
}
?>
