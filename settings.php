<?PHP
/******************************************
 * Einstellungsdatei für Quorra
 *
 * @author		Tim Jaap <tim.jaap@mailbox.tu-berlin.de>
 * @version		0.1 (beta)
 ******************************************/
// Allgemeine Informationen zu Quorra
$quorra_lib_path			= "lib/";

$quorra_url					= "http://localhost/html/quorra/test.php";

// Informationen zum mediaWiki, das über Quorra verwaltet werden soll
//$mw_webaddress				= "192.168.10.6";
$mw_webaddress				= "localhost";
$mw_path					= "wiki/";
$mw_cookieprefix			= "be-wiki";

$mw_apiUrl					= "http://" . $mw_webaddress . "/" . $mw_path . "api.php5";
?>
