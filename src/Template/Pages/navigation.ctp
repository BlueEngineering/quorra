<?php
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;

$this->layout = true;

if (!Configure::read('debug')):
    throw new NotFoundException();
endif;

?>
<div class="container">
aallllooo
</div>