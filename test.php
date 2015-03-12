<?PHP
/*******
 *
 
 *******/
//
session_start();

// includes
require_once("settings.php");
require_once("lib/mw_api/mw_api.php");
require_once("lib/user/ec_user.php");

/*
echo "Testuser 1 erstellt<br />";

$u1		= new ec_user( "1", "Tim", "ascony@arcor.de" );

echo "Name von Testuser 1: <i>" . $u1->get_user_name() . "</i><br />";

echo "Speichern des Testuserobjekts in die Session<br />";
$_SESSION["user"]	= $u1;
echo "Testen ob Objekt in Session:<br /><pre>";
print_r($_SESSION);
echo "</pre>";
echo $_SESSION["user"]->get_user_name() . "<br /><br />";

//
echo "Test mit Array als return value<br />";
$api	= new mw_api( "https://localhost" );

echo "<pre>";
print_r( $api->mw_api_login( "hans", "wurst" ) );
echo "</pre>";

echo "RESTful Wiki API Testszenario<br /><br />";
*/
//echo mw_api_getEditToken();
		//$ch			= curl_init( $this->mwAPI_url );
		$ch = curl_init( $mw_apiUrl );
		
		// set cURL options
		curl_setopt( $ch, CURLOPT_URL, "?action=query&meta=token" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		
		// start cURL process to get an edit token from mediawiki system
		//$curl_res	= simplexml_load_string( curl_exec( $ch ) );
		//echo "bla";
		curl_exec( $ch );
		
		// close cURL session
		curl_close( $ch );

//
//$user_name		= "";
//$user_password	= "";

//echo "Teste Login<br />";
//mwApi_login( $user_name, $user_password ) . "<br />";
//echo "Login Status: " . mwApi_isLogged() . "<br /><br />";

//echo "Führe eine Testnews durch<br />";
/*
if ( mwApi_isLogged() ) {
	//mwApi_logout();
}
*/
// Testeintrag ins Wiki

$sum		= urlencode( "Hello World!" );
$text		= urlencode( "<br />Dies ist ein Test<p>Bitte '''gehen''' <i>Sie</i> weiter!</p>" );
$query_end	= urlencode( "+\\" );


//echo $query_end;

//echo "<br />\n";
//mwApi_getEditToken();
//$ch		= curl_init();
//curl_setopt( $ch, CURLOPT_ )
//curl_setopt( $ch, CURLOPT_URL, "http://localhost/wiki/api.php?action=query&meta=tokens&type=csrf|watch&rawcontinue=" );
//curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
//$muh = curl_exec( $ch );
//curl_exec( $ch );
//echo curl_getinfo( $muh );
//echo $_GET["token"];


//echo "<pre>";
//echo $muh;
//echo "</pre>";

//$xml	= simplexml_load_string( curl_exec( $ch ) );
//echo $xml->query->tokens->csrftoken->attributes()->pageid;

//curl_close( $ch );


// cURL - Funktioniert soweit... Testen mit Userrechte notwendig! 
/*$ch	= curl_init();
curl_setopt( $ch, CURLOPT_POST, true );
curl_setopt( $ch, CURLOPT_URL, "http://localhost/wiki/api.php" );
curl_setopt( $ch, CURLOPT_POSTFIELDS, "action=edit&title=Testman&section=new&summary=". $sum ."&text=" . $text . "&watch&token=" . $query_end );
//curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
curl_exec( $ch );

//$xml	= simplexml_load_string( curl_exec( $ch ) );
//echo $xml->query->pages->page->attributes()->pageid;
curl_close( $ch );
*/
?>
