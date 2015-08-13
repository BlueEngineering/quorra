<?php
/************************************************************************************
 * component for CakePHP to send emails to users
 *
 * @author	Tim Jaap	<tim.jaap@mailbox.tu-berlin.de>
 * @version	1.0
 ************************************************************************************/
//
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Network\Email\Email;

class EmailSendingComponent extends Component {
	/************************************************************************************
	 * function to generate a sending email
	 *
	 * @param	$sender,	mail address where sending from
	 * @param	$receiver,	mail address from receiver
	 * @param	$subject,	the subject of email
	 * @param	$text,		text of contentbody
	 *
	 * @return	boolean
	 ************************************************************************************/
	public function send_email( $sendermail, $sendername, $receiver, $subject, $text ) {
		// sending email
		$sendEMail	= new Email( 'default' );
		
		$sendEMail->from( [ $sendermail => $sendername ] )
			->to( $receiver )
			->subject( $subject )
			->send( $text );
	
		return;
	}
	
	/************************************************************************************
	 * function to parse given text and replace elements
	 *
	 * @param	$text,			the text where parsing
	 * @param	$replacements,	an array with given replacementelements
	 *
	 * @return	$text,	the parsed text where control commands are replaced with variables
	 ************************************************************************************/
	public function parsing_mailtext( $text, $replacements ) {
		// define needles
		$needles	= array( '%name', '%username', '%userpass', '%urlSeminar', '%urlWiki' );
		
		// check if given text empty
		if( empty( $text ) ) {
			return false;
		}
		
		// return replacement string
		return str_replace( $needles, $replacements, $text );
	}
}
?>