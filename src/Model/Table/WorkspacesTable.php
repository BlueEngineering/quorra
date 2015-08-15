<?php
/**
 * MVC model for seminars
 *
 * @author		Tim Jaap <tim.jaap@mailbox.tu-berlin.de>
 * @version		1.0
 */

// set namespace
namespace App\Model\Table;

// load cake components
use Cake\ORM\Table;
use Cake\Validation\Validator;

class SeminarsTable extends Table {
	/**
	 * validation function
	 */
	public function validationDefault( Validator $validator ) {
		return $validator
			->notEmpty( 'name', 'Der Name des Seminars muss angegeben werden.' )
			->notEmpty( 'namespace', 'Es muss ein Kürzel angegeben werden.' )
			->notEmpty( 'tutgrp', 'Der Name der Gruppe für die Tutor_innen muss angegeben werden.' )
			->notEmpty( 'memgrp', 'Der Name der Gruppe für die Teilnehmer_innen muss angegeben werden.' )
			->notEmpty( 'mailsender', 'Es muss eine Absendeadresse angegeben werden.' )
			->notEmpty( 'mailsubject', 'Es muss ein Betreff für die E-Mail angegeben werden.' )
			->notEmpty( 'mailtext', 'Der Inhalt der E-Mail darf nicht leer sein.' );
	}
	
	/**
	 * init function for relationships between data models
	 *
	 * @param	array $config, configuration informations
	 */
	public function initialize( array $config ) {
		/*
		// define hasMany relationsship between user and (user-)groups
		$this->hasMany( 'Groups', [
			'foreignKey'	=> 'name',
			'bindingKey'	=> 'groups'
		] );
		*/
	}
}
?>