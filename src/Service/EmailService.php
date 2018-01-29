<?php
/**
 * Created by PhpStorm.
 * User: reberme
 * Date: 22/01/2018
 * Time: 16:40
 */

namespace Drupal\rir_interface\Service;


use Drupal;
use Drupal\Core\Render\Markup;
use Drupal\node\NodeInterface;
use Drupal\rir_interface\Utils\Constants;

class EmailService
{

    /**
     * @param $data
     */
    public function send($data)
    {
        if ($data->notificationType === Constants::ADVERT_VALIDATED) {
            $entity = $data->entity;
            if ($entity instanceof NodeInterface) {
                $contact_email = $entity->get('field_advert_contact_email')->value;
                $visit_email_1 = $entity->get('field_visit_email_address1')->value;
                $visit_email_2 = $entity->get('field_visit_email_address2')->value;
                $recipients = $contact_email;

                if (isset($visit_email_1) and !empty($visit_email_1)) {
                    $recipients .= ',' . $visit_email_1;
                }
                if (isset($visit_email_2) and !empty($visit_email_2)) {
                    $recipients .= ',' . $visit_email_2;
                }

                $mailManager = Drupal::service('plugin.manager.mail');
                $module = 'rir_interface';
                $key = 'advert_first_published';
                $to = $recipients;
                $reply = Drupal::config('system.site')->get('mail');
                $params['cc'] = Drupal::config('system.site')->get('mail');
                $params['message'] = Markup::create(getEmailHtmlContent('advert_validated', $entity));
                $params['advert_title'] = $entity->label();
                $params['contact_name'] = $entity->get('field_visit_contact_name')->value;

                $attachments = get_email_attachment_files();
                $params['attachments'][] = $attachments[0];
                $params['attachments'][] = $attachments[1];

                $langcode = Drupal::currentUser()->getPreferredLangcode();
                $send = TRUE;
                $result = $mailManager->mail($module, $key, $to, $langcode, $params, $reply, $send);
                if (intval($result['result']) !== 1) {
                    $message = t('There was a problem sending notification email after creating advert id: @id.', [
                        '@id' => $entity->id(),
                    ]);
                    Drupal::logger('rir_interface')
                        ->error($message . ' Whole Error: ' . json_encode($result, TRUE));
                } else {
                    $message = t('An email notification has been sent after creating advert id: @id.', [
                        '@id' => $entity->id(),
                    ]);
                    Drupal::logger('rir_interface')->notice($message);
                }
            } else {
                Drupal::logger('rir_interface')->error('Advert validated notification: Wrong entity type');
            }
        }
    }

}