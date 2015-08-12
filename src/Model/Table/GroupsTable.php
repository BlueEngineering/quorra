<?php
/**
 * MVC model for usergroups
 *
 * @author		Tim Jaap <tim.jaap@mailbox.tu-berlin.de>
 * @version		1.0
 */

// set namespace
namespace App\Model\Table;

// load cake components
use Cake\ORM\Table;
use Cake\Validation\Validator;

class GroupsTable extends Table {
	/**
	 * validation function
	 */
	public function validationDefault( Validator $validator ) {
		return $validator
			->notEmpty( 'name', 'Der Benutzergruppenname muss angegeben werden.' )
			->allowEmpty( 'courses' )
			->allowEmpty( 'users' );
			//->add( 'courses' )
			//->add( 'users' )
	}
	
}
?>