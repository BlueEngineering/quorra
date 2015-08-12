<?php
/************************************************************************************
 * MVC-Tablemodel for channels from news center
 *
 * @author	Tim Jaap	<tim.jaap@mailbox.tu-berlin.de>
 * @version	1.0
 ************************************************************************************/
//
namespace App\Model\Table;

//
use Cake\ORM\Table;

class NewschannelsTable extends Table {
	//
	/*
	public function validationDefault( Validator $validator ) {
		//
		$validator
			->notEmpty( 'id' )
			->notEmpty( 'name' )
			->notEmpty( 'mw_article_id' )
			->add('email', [ 'unique' => [ 'rule' => ['validateUnique', ['scope' => 'site_id'] ], 'provider' => 'table' ] ] );
		//
		return $validator;
	}
	*/
}
?>