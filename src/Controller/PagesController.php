<?php
/**
 * Created by PhpStorm.
 * User: reberme
 * Date: 09/11/2017
 * Time: 17:21
 */

namespace Drupal\rir_interface\Controller;


use Drupal\Core\Controller\ControllerBase;

class PagesController extends ControllerBase {

		public function hirManagementPage(){
				$element = array(
					'#markup' => 'Hello HiR...'
				);
				return $element;
		}

}