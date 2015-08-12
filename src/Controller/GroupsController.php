<?php
/************************************************************************************
 * MVC-Controller for administrated groups.
 *
 * @author	Tim Jaap	<tim.jaap@mailbox.tu-berlin.de>
 * @version	1.0
 ************************************************************************************/
//
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use App\Component\MediawikiAPIComponent;

class GroupsController extends AppController {
	/************************************************************************************
	 *
	 *
	 ************************************************************************************/
	public function initialize() {
		parent::initialize();
		$this->loadComponent( 'MediawikiAPI' );
	}
}
?>