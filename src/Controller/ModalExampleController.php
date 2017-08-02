<?php
/**
 * Created by PhpStorm.
 * User: medard
 * Date: 02.08.17
 * Time: 15:46
 */

namespace Drupal\rir_interface\Controller;


use Drupal\Component\Serialization\Json;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

class ModalExampleController extends ControllerBase {
  public function page(){
    $link_url = Url::fromRoute('rir_interface.modal', ['js' => 'nojs']);
    $link_url->setOptions([
      'attributes' => [
        'class' => ['use-ajax', 'button', 'button--small'],
        'data-dialog-type' => 'modal',
        'data-dialog-options' => Json::encode(['width' => 400])
      ]
    ]);

    return array(
      '#type' => 'markup',
      '#markup' => Link::fromTextAndUrl($this->t('Open the modal'), $link_url)->toString(),
      '#attached' => ['library' => ['core/drupal.dialog.ajax']]
    );
  }

  public function modal($js  = 'nojs'){
    if ($js == 'ajax'){
      $options = [
        'dialogClass' => 'popup-dialog-class',
      ];
      $response = new AjaxResponse();
      $response->addCommand(new OpenModalDialogCommand($this->t('Modal title'),
        $this->t('This is an example of a modal with Javascript'), $options));
      return $response;
    } else {
      return $this->t('This is an example of a fallback for a modal without Javascript');
    }
  }
}