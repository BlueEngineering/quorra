<?PHP
/************************************************************************************
 * Testing class for mw_api.php
 *
 * @author		Tim Jaap <tim.jaap@mailbox.tu-berlin.de>
 * @version		0.1 (beta)
 ************************************************************************************/
// ----- includes -----
require_once( "../settings.php" );
require_once( "../lib/mw_api/mw_api.php" );

// ----- tesing data -----
$username			= "testfiy";
$userpass			= "killme";

// ----- create a controller object -----
$mw_be_controller	= new mw_api( $mw_apiUrl, $mw_cookieprefix );

// ----- object testing -----
/*
echo "<h1>Objecttesting</h1>";
echo "<b>URL from mediaWiki: </b>" . $mw_be_controller->mw_api_getUrl() . "<br />";
echo "<b>Prefix of mediaWiki cookies:</b> " . $mw_be_controller->mw_api_getCookieprefix() . "<br />";
echo "<hr />";

// ----- test login functions -----
echo "<h1>Test der Loginfunktionen</h1>";

// test mw_api_isLogged()
echo "<h2>Test mw_api_isLogged()</h2>";
echo "<b>Aktueller Status Login:</b> ";
echo ( $mw_be_controller->mw_api_isLogged()[0] == false ) ? '<span style="color:green">ausgeloggt</span>' : '<span style="color:red">eingeloggt</span>';
echo " (erwartete Rueckgabe: ausgeloggt)<br />";

// test mw_api_login()
echo "<h2>Test mw_api_login()</h2>";
*/
/*echo "<b>Loginversuch mit falschem Benutzername:</b> ";
echo ( $mw_be_controller->mw_api_login( "pipapo", $userpass )[0] ) ? '<span style="color:red">true</span>' : '<span style="color:green">false</span>';
echo " (erwartete Rueckgabe: false)";
echo "<b>Loginversuch mit falschem Passwort:</b> ";
echo ( $mw_be_controller->mw_api_login( $username, "abc123" )[0] ) ? '<span style="color:red">true</span>' : '<span style="color:green">false</span>';
echo " (erwartete Rueckgabe: false)";*/
//echo "<b>Loginversuch mit korrekten Daten:</b> " . $mw_be_controller->mw_api_login( $username, $userpass )[1];
//echo ( $mw_be_controller->mw_api_login( $username, $userpass )[0] == true ) ? '<span style="color:green">true</span>' : '<span style="color:red">false</span>';
//echo " (erwartete Rueckgabe: true)"; 
$mw_be_controller->mw_api_login( $username, $userpass );
// test mw_api_isLogged()

// test mw_api_logout()

// test mw_api_login()
?>
