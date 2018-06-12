<?php

namespace Drupal\maptown\Controller;

use Drupal\Core\Controller\ControllerBase;

class MapTownController extends ControllerBase{
	public function content(){
		return['#markup' => $this->t('coucou map')];
	}
}