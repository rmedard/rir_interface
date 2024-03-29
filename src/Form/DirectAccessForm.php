<?php
/**
 * Created by PhpStorm.
 * User: medard
 * Date: 27.07.17
 * Time: 22:00
 */

namespace Drupal\rir_interface\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\node\NodeInterface;
use function count;

class DirectAccessForm extends FormBase
{

    /**
     * Returns a unique string identifying the form.
     *
     * @return string
     *   The unique string identifying the form.
     */
    public function getFormId(): string
    {
        return 'direct_access_form';
    }

    /**
     * Form constructor.
     *
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param FormStateInterface $form_state
     *   The current state of the form.
     *
     * @return array
     *   The form structures.
     */
    public function buildForm(array $form, FormStateInterface $form_state): array
    {
        $form['reference_number'] = array(
            '#type' => 'textfield',
            '#attributes' => array(
                'placeholder' => $this->t('Reference number'),
                'size' => 30,
                'maxlength' => 30
            ));
        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Find')
        );
        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        if ($form_state->isValueEmpty('reference_number')) {
            $form_state->setErrorByName('reference_number', t('Provide reference number'));
        }
    }

    /**
     * Form submission handler.
     *
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param FormStateInterface $form_state
     *   The current state of the form.
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $reference = trim($form_state->getValue('reference_number'));
        $nodeQuery = Drupal::entityQuery('node')
            ->condition('type', 'advert')
            ->condition('status', NodeInterface::PUBLISHED)
            ->condition('field_advert_reference', $reference);
        $node_ids = $nodeQuery->execute();
        if (isset($node_ids) and !empty($node_ids)) {
            if (count($node_ids) == 1) {
                foreach ($node_ids as $node_id) {
                    $advert_url = Url::fromRoute('entity.node.canonical', ['node' => $node_id]);
                    $form_state->setRedirectUrl($advert_url);
                    break;
                }
            } else {
                // Should not happen
                Drupal::logger('rir_interface')
                    ->error($this->t("Oops, more than one advert have reference number: @reference . Please report this issue to the admin.", array('@reference' => $reference)));
            }
        } else {
            Drupal::logger('rir_interface')->error($this->t("Sorry, no advert found with reference number: @reference", array('@reference' => $reference)));
        }
    }
}
