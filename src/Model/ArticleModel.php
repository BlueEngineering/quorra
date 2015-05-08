<?php
/************************************************************************************
 * MVC-Models of articles
 *
 * @author	Tim Jaap	<tim.jaap@mailbox.tu-berlin.de>
 * @version	1.0
 ************************************************************************************/
//
namespace App\Model;

use Cake\ORM\Entity;
//use App\Model\AppModel;

class ArticleModel extends Entity {
	//
	private	$hauskatze;
	
	//
	public function ArticleModel() {
		$this->hauskatze = 'Mausi';
	}
	
	public function getArticleByID( $id ) {
		//
		return array( 'id' => 1, 'title' => 'Die ist ein Testartikel', 'content' => 'Dies ist ein Testinhalt' );
	}
	
	/*
	public $_schema	= array(
								'id'		=> array(
													'type'		=> 'integer',
													'length'	=> 100
													),
								'title'		=> array(
													'type'		=> 'string',
													'length'	=> 250,
													)
							);
	*/
}
?>