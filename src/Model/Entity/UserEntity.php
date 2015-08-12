<?php
/************************************************************************************
 * MVC-Entitymodel for a channel from news center
 *
 * @author	Tim Jaap	<tim.jaap@mailbox.tu-berlin.de>
 * @version	1.0
 ************************************************************************************/
//
namespace App\Model\Entity;

//
use Cake\ORM\Entity;

class UserEntity extends Entity {
	/**
	 * init function for relationships between data models
	 *
	 * @param	array $config, configuration informations
	 */
	public function initialize( array $config ) {
		// define hasMany relationsship between user and (user-)groups
		$this->hasMany( 'Groups', [
			'foreignKey'	=> 'name',
			'bindingKey'	=> 'groups'
		] );
	}
}
?>