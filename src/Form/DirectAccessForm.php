<?php
/**
 * Created by PhpStorm.
 * User: medard
 * Date: 27.07.17
 * Time: 22:00
 */

namespace Drupal\rir_interface\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\t;
use \Drupal\Core\Form\drupal_set_message;
use \Drupal\Core\Url;

class DirectAccessForm extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'direct_access_form';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['reference_number'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Reference'));
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Find')
    );
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->isValueEmpty('reference_number')){
      $form_state->setErrorByName('reference_number', t('Provide reference number'));
    }
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $reference = $form_state->get('reference_number');
    var_dump("dore" .$reference);
    die();
    $nodeQuery = \Drupal::entityQuery('node')
      ->condition('type', 'advert')
      ->condition('status', 1)
      ->condition('field_advert_reference', $reference);
    $node = $nodeQuery->execute();
    if (isset($node)){
      $url = Url::fromUri('internal:/advert/QL7XT9TBN');
      $form_state->setRedirectUrl($url);
    } else {
      drupal_set_message($this->t("Sorry, no advert found"), 'error');
    }
  }
}