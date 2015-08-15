<?php
/**
 * MVC model for users
 *
 * @author		Tim Jaap <tim.jaap@mailbox.tu-berlin.de>
 * @version		1.0
 */

// set namespace
namespace App\Model\Table;

// load cake components
use Cake\ORM\Table;
use Cake\Validation\Validator;

class UsersTable extends Table {
	/**
	 * validation function
	 */
	public function validationDefault( Validator $validator ) {
		return $validator
			->notEmpty( 'username', 'Der Benutzername muss angegeben werden.' )
			->notEmpty( 'email', 'Es wird eine E-Mail Adresse benötigt.' )
			->allowEmpty( 'realname' );
	}
	
	/**
	 * init function for relationships between data models
	 *
	 * @param	array $config, configuration informations
	 */
	public function initialize( array $config ) {
		// define hasMany relationsship between users and other tables
		$this->hasMany( 'Groups', [
			'foreignKey'	=> 'name',
			'bindingKey'	=> 'groups'
			]
		);
		$this->hasMany( 'Workspaces', [
			'foreignKey'	=> '',
			'bindingKey'	=> ''
			]
		);
	}
}
?>