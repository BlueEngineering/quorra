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
	 * method to log in mediawiki system.
	 *
	 * @param	User	$user
	 * @return	Array	( Boolean $status, Int $err_code, String $token)
	 ************************************************************************************/
	public function mw_api_login( $username, $userpass ) {
		// check if user is already logged
		if( mw_api_isLogged()[0] == true ) {
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
			setcookies( $this->mwAPI_cookieprefix . "UserName", "", time() - 3600, "/" );
			setcookies( $this->mwAPI_cookieprefix . "_session", "", time() - 3600, "/" );
			setcookies( $this->mwAPI_cookieprefix . "UserID", "", time() - 3600, "/" );
			setcookies( $this->mwAPI_cookieprefix . "Token", "", time() - 3600, "/" );
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
		// check if user is not logged
		if( mw_api_isLogged() == false ) {
			return array( false, 1 );
		}
		
		// delete cookies
		setcookies( $this->mwAPI_cookieprefix . "UserName", "", time() - 3600, "/" );
		setcookies( $this->mwAPI_cookieprefix . "_session", "", time() - 3600, "/" );
		setcookies( $this->mwAPI_cookieprefix . "UserID", "", time() - 3600, "/" );
		
		return array( true, 0 );
	}
	
	/************************************************************************************
	 * method to get an edit token from mediawiki system
	 *
	 * @return	Array	( Boolean $status, Int $err_code )
	 ************************************************************************************/
	public function mw_api_getEditToken() {
		// initial cURL session
		$ch			= curl_init( $this->mwAPI_url );
		
		// set cURL options
		curl_setopt( $ch, CURLOPT_URL, "?action=query&meta=token" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		
		// start cURL process to get an edit token from mediawiki system
		$curl_res	= simplexml_load_string( curl_exec( $ch ) );
		
		// close cURL session
		curl_close( $ch );
		
		// return result
		return $curl_res; //->query->pages->page->attributes()->pageid;
	}
	
	/************************************************************************************
	 * method to create/edit a mediawiki article.
	 * 
	 * @param	String	$title
	 * @param	String	$
	 ************************************************************************************/
	
	/************************************************************************************
	 * 
	 * 
	 ************************************************************************************/
	
	/************************************************************************************
	 * 
	 * 
	 ************************************************************************************/
	
	/************************************************************************************
	 * 
	 * 
	 ************************************************************************************/
}
?>
