<?php
/************************************************************************************
 * password generator component for CakePHP
 * based on http://stackoverflow.com/questions/22828543/dynamic-password-generation-in-php
 *
 * @author	Tim Jaap	<tim.jaap@mailbox.tu-berlin.de>
 * @version	1.0
 ************************************************************************************/
//
namespace App\Controller\Component;

use Cake\Controller\Component;

class PasswordGeneratorComponent extends Component {
	/************************************************************************************
	 * function to generator a random password
	 *
	 * @param	$length, default value is 8 and give length of generating password
	 *
	 * @return	$password
	 ************************************************************************************/
	public function generate_password( $length = 8 ) {
		// character pool
		$chars		= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	
		return substr( str_shuffle( $chars ), 0, $length );
	}
}
?>