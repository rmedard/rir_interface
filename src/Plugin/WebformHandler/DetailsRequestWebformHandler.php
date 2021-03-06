<?php
/**
 * Created by PhpStorm.
 * User: medard
 * Date: 13.08.17
 * Time: 01:03
 */

namespace Drupal\rir_interface\Plugin\WebformHandler;

use Drupal;
use Drupal\webform\Plugin\WebformHandler\EmailWebformHandler;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Class DetailsRequestWebformHandler
 *
 * @package Drupal\rir_interface\Plugin\WebformHandler
 * @WebformHandler(
 *   id = "details_request_email",
 *   label = @Translation("Details Request Email"),
 *   category = @Translation("Notification"),
 *   description = @Translation("Sends webform submission to a contact email address."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 * )
 */
class DetailsRequestWebformHandler extends EmailWebformHandler
{

    public function sendMessage(WebformSubmissionInterface $webform_submission, array $message)
    {
        $node = Drupal::routeMatch()->getParameter('node');
        if (isset($node)) {
            $contact_name = $node->get('field_visit_contact_name')->value;
            $phone = $webform_submission->getElementData('visitor_phone_number');
            $email = $webform_submission->getElementData('visitor_email');
            $names = $webform_submission->getElementData('visitor_names');
            $email_message = $webform_submission->getElementData('visitor_message');

            $options = array(
                'langcode' => Drupal::currentUser()->getPreferredLangcode(),
            );

            $recipients = $node->get('field_visit_email_address1')->value;
            if (isset($node->get('field_visit_email_address2')->value) and !empty($node->get('field_visit_email_address2')->value)) {
                $recipients .= ',' . $node->get('field_visit_email_address2')->value;
            }
            $message['to_mail'] = $recipients;
            $message['reply_to'] = $email;
            $message['subject'] = $this->t('Request for more details about your property on @site',
                array('@site' => Drupal::config('system.site')->get('name')), $options);
            $message['html'] = TRUE;

            $message['body'] = getHtmlContent($node, $contact_name, $phone, $email, $names, $email_message);
            Drupal::logger('rir_interface')->debug('Request for further info sent by: ' . $email);
        } else {
            Drupal::logger('rir_interface')->warning("Request for info sent with empty advert node.");
        }
        return parent::sendMessage($webform_submission, $message);
    }
}

function getHtmlContent($advert, $contact_name, $phone, $email, $names, $message)
{
    $variables = [
        'advert' => $advert,
        'contact_name' => $contact_name,
        'phone' => $phone,
        'email' => $email,
        'names' => $names,
        'message' => $message
    ];
    $twig_service = Drupal::service('twig');
    return $twig_service->loadTemplate(drupal_get_path('theme', 'rir') . '/emails/rir-request-info.html.twig')->render($variables);
}