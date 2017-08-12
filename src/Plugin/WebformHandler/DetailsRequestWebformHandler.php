<?php
/**
 * Created by PhpStorm.
 * User: medard
 * Date: 13.08.17
 * Time: 01:03
 */

namespace Drupal\rir_interface\Plugin\WebformHandler;


use Drupal\webform\Plugin\WebformHandler\EmailWebformHandler;
use Drupal\webform\WebformSubmissionInterface;

class DetailsRequestWebformHandler extends EmailWebformHandler {

  public function sendMessage(WebformSubmissionInterface $webform_submission, array $message) {
    $node = \Drupal::routeMatch()->getParameter('node');
    if (isset($node)){
      $recipient = $node->get('field_advert_contact_email')->value;
      $message['to_mail'] = $recipient;
    }
    return parent::sendMessage($webform_submission, $message);
  }
}