<?PHP
/*******
 *
 
 *******/
// includes
//require_once("settings.php");
//require_once("lib/mw_api.php");

//echo "RESTful Wiki API Testszenario<br /><br />";

//
$user_name		= "Tim.Jaap";
$user_password	= "L1ikM3n0w";

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
$ch	= curl_init();
curl_setopt( $ch, CURLOPT_POST, true );
curl_setopt( $ch, CURLOPT_URL, "http://localhost/wiki/api.php" );
curl_setopt( $ch, CURLOPT_POSTFIELDS, "action=edit&title=Testman&section=new&summary=". $sum ."&text=" . $text . "&watch&token=" . $query_end );
//curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
curl_exec( $ch );

//$xml	= simplexml_load_string( curl_exec( $ch ) );
//echo $xml->query->pages->page->attributes()->pageid;
curl_close( $ch );

?>
