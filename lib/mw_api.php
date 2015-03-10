<?PHP
/************************************************************************************
 * Controllerclass: Interface to mediaWiki API.
 *
 * @author		Tim Jaap <tim.jaap@mailbox.tu-berlin.de>
 * @version		0.1 (beta)
 ************************************************************************************/
//
class mw_API {
	// attributes
	private	$mwAPI_url;
	//private $mwAPI_
}


/************************************************************************************
 *
 *
 ************************************************************************************/
 /*
function mwApi_login( $username, $userpass ) {
	// Mache globale Variablen bekannt (vor allem Einstellungsvariablen)
	global $mw_cookieprefix, $mw_apiUrl;
	
	// lokale Variablen
	$xmlres			= "";						// Variable zum Speichern der XML Ergebnisse
	$mw_result		= "";						// Variable zum Speichern des Ergebnisses vom Loginversuch
	$mw_token		= "";						// Variable zum Speichern des Tokens
	$mw_session		= "";						// Variable zum Speichern der Session ID
	$mw_userid		= "";						// Variable zum Speichern der User ID

	// Prüfe, ob Benutzer ggf schon eingeloggt ist
	if ( mwAPI_isLogged() == true ) {
		return "Du bist bereits eingeloggt.";
	}
	
	// Erzeuge einen cURL Handler
	$curl_handler	= curl_init( $mw_apiUrl );
	
	// Setze POSTFIELD Informationen für cURL
	curl_setopt( $curl_handler, CURLOPT_POST, true );
	curl_setopt( $curl_handler, CURLOPT_POSTFIELDS, "action=login&lgname=" . $username . "&lgpassword=" . $userpass . "&format=xml" );
	curl_setopt( $curl_handler, CURLOPT_RETURNTRANSFER, true );
	
	// Führe cURL Aktion aus und speichere die Rückgabe
	$xmlresa	= simplexml_load_string( curl_exec( $curl_handler ) );
	
	$mw_session	= $xmlresa->login->attributes()->sessionid;
	$mw_token	= $xmlresa->login->attributes()->token;
	$mw_result	= $xmlresa->login->attributes()->result;
	
	// Erstelle die notwendigen COOKIES mit Gültigkeitsdauer von 60 Tagen
	setcookie( $mw_cookieprefix . "UserName", $username, time() + 3600 * 24 * 60, "/" );
	setcookie( $mw_cookieprefix . "_session", $mw_session, time() + 3600 * 24 * 60, "/" );
	
	// Führe Loginversuch mit übergebenen Daten durch
	curl_setopt( $curl_handler, CURLOPT_POST, true );
	curl_setopt( $curl_handler, CURLOPT_COOKIE, $mw_cookieprefix . "_session=" . $mw_session );
	curl_setopt( $curl_handler, CURLOPT_POSTFIELDS, "action=login&lgname=" . $username . "&lgpassword=" . $userpass . "&lgtoken=" . $mw_token . "&format=xml" );
	curl_setopt( $curl_handler, CURLOPT_RETURNTRANSFER, true );
	
	// Probiere Login
	$xmlresb		= simplexml_load_string( curl_exec( $curl_handler ) );
	$mw_resultb		= $xmlresb->login->attributes()->result;
	$mw_userid		= $xmlresb->login->attributes()->lguserid;
	
	// Prüfe, ob Login erfolgreich war
	if ( $mw_resultb == "Success" ) {
		// Erzeuge UserID und Token Cookie und lösche LoggedOut Cookie
		setcookie( $mw_cookieprefix . "UserID", $mw_userid, time() + 3600 * 24 * 60, "/" );
		setcookie( $mw_cookieprefix . "Token", $mw_token, time() + 3600 * 24 * 60, "/" );
		setcookie( $mw_cookieprefix . "LoggedOut", "", time() - 3600, "/" );
	} else {
		// Lösche die Erzeugten Cookies UserName, _session und UserID falls die Anmeldung fehlgeschlagen ist.
		setcookie( $mw_cookieprefix . "UserName", "", time() - 3600, "/" );
		setcookie( $mw_cookieprefix . "_session", "", time() - 3600, "/" );
		setcookie( $mw_cookieprefix . "UserID", "", time() - 3600, "/" );
		setcookie( $mw_cookieprefix . "Token", "", time() - 3600, "/" );
	}
	
	// Schließe cURL-Session
	curl_close( $curl_handler );
	
	// Gehe mögliche Rückgabewerte durch
	switch ( $mw_resultb ) {
		// Verlorener Token
		case "NeedToken":
			return "Es wurde kein Token übergeben.";
			
		// Login erfolgreich
		case "Success":
			return "Login erfolgreich.";
			
		// Fehler: Kein Benutzername übergeben
		case "NoName":
			return "Du hast kein Benutzernamen angegeben. Bitte überprüfe deine Eingaben und probiere es erneut.";
			
		// Fehler: Benutzername ungültig
		case "Illegal":
			return "Der von dir eingegebene Benutzer_inname ist ungültig. Bitte überprüfe deine Eingaben und probiere es erneut.";
		
		// Fehler: Benutzername existiert nicht
		case "NotExists":
			return "Der von dir eingegebene Benutzer_inname existiert nicht. Bitte überprüfe deine Eingaben und probiere es erneut.";
		
		// Fehler: Kein Passwort übergeben
		case "EmptyPass":
			return "Du hast kein Passwort eingegeben. Bitte überprüfe deine Eingaben und probiere es erneut.";
		
		// Fehler: Falsches Passwort
		case "WrongPass":
			return "Dein eingegebenes Passwort ist falsch. Bitte überprüfe deine Eingaben und probiere es erneut.";
		
		// Fehler: Plugin meldet falsches Passwort
		case "WrongPluginPass":
			return "Eines der Erweiterungen Erweiterungen des mediaWikis meldet, dass dein Passwort falsch ist. Bitte überprüfe deine Eingaben und probiere es erneut.";
		
		// Fehler: Neuer Account konnte nicht angelegt werden, IP wird geblockt
		case "CreateBlocked":
			return "Der von dir angegebene Benutzer_inname existiert nicht und konnte nicht angelegt werden, weil deine IP-Adresse gesperrt ist.";
		
		// Fehler: Loginsperre wegen zu vielen Fehlversuchen
		case "Throttled":
			return "Du hast fünf mal (5x) falsch Logindaten eingetragen. Aus Sicherheitsgründen musst du nun 5 Minuten warten. Bitte überprüfe deine Eingaben und probiere es erneut.";
		
		// Sonstiger Fall
		default:
			return "Ein unbekannter Fehler ist aufgetreten.";
	}
}
*/

/************************************************************************************
 *
 *
 ************************************************************************************/
 /*
function mwApi_logout() {
	// Mache globale Variablen bekannt (vor allem Einstellungsvariablen)
	global $quorra_url, $mw_cookieprefix;
	
	// Verlasse Funktion wenn Benutzer_in nicht eingeloggt ist.
	if ( mwApi_isLogged() == false ) {
		return "Logout nicht möglich, du bist nicht eingeloggt.";
	}
	
	// Lösche Cookies
	setcookie( $mw_cookieprefix . "UserName", "", time() - 3600, "/" );
	setcookie( $mw_cookieprefix . "_session", "", time() - 3600, "/" );
	setcookie( $mw_cookieprefix . "UserID", "", time() - 3600, "/" );
	
	// Leite auf Startseite weiter
	header( "Location: " . $quorra_url );
	
	// Fehlerfall
	return "Es ist ein unbekannter Fehler während des Logout-Prozesses aufgetreten.";
}
*/


/************************************************************************************
 *
 *
 ************************************************************************************/
 /*
function mwApi_isLogged() {
	// Mache globale Variablen bekannt (vor allem Einstellungsvariablen)
	global $mw_cookieprefix;
		
	// Prüfen, ob die mediawiki-typischen Cookies bereits gesetzt sind
	if ( isset( $_COOKIE[$mw_cookieprefix ."UserID"] ) && isset( $_COOKIE[$mw_cookieprefix ."UserName"] ) && isset( $_COOKIE[$mw_cookieprefix ."_session"] ) ) {
		return true;
	}
	
	return false;
}
*/

/************************************************************************************
 *
 *
 ************************************************************************************/
 /*
function mwApi_getEditToken() {
	// global
	global $mw_cookieprefix, $mw_apiUrl;
	
	//mwApi_isLogged();
	echo $_COOKIE[ $mw_cookieprefix . "Token"];
	
	//
	//echo "<br />";
	//echo "eee";
	$ch		= curl_init( $mw_apiUrl ); 
	
	curl_setopt( $ch, CURLOPT_URL, "?action=query&meta=token" );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	
	//$xml	= simplexml_load_string( curl_exec( $ch ) );
		//echo $xml->query->pages->page->attributes()->pageid;
	curl_close( $ch );
}
*/
?>
