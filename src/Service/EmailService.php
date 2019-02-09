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
        $mailManager = Drupal::service('plugin.manager.mail');
        $module = 'rir_interface';
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
                $params['attachments'][] = $attachments[2];

                $langcode = Drupal::languageManager()->getDefaultLanguage()->getId();
                $send = TRUE;
                $result = $mailManager->mail($module, $key, $to, $langcode, $params, $reply, $send);
                if (intval($result['result']) != 1) {
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
        } elseif ($data->notificationType === Constants::ADVERT_VALIDATED_NOTIFY_PR) {
            $entity = $data->entity;
            Drupal::logger('PR Notification')->notice('About to send notification...');
            if ($entity instanceof NodeInterface) {
                $key = Constants::ADVERT_VALIDATED_NOTIFY_PR;

                $PRsService = Drupal::service('rir_interface.property_requests_service');
                $PRs = $PRsService->loadPRsForAdvert($entity);
                if (isset($PRs) and !empty($PRs)) {
                    foreach ($PRs as $pr) {
                        if ($pr instanceof NodeInterface) {
                            $to = $pr->get('field_pr_email')->value;
                            $reply = Drupal::config('system.site')->get('mail');
                            $params['cc'] = Drupal::config('system.site')->get('mail');
                            $params['message'] = Markup::create(getEmailHtmlContent('advert_validated_notify_pr',
                                $entity, $pr->get('field_pr_first_name')->value));
                            $langcode = Drupal::languageManager()->getDefaultLanguage()->getId();
                            $send = TRUE;
                            $result = $mailManager->mail($module, $key, $to, $langcode, $params, $reply, $send);
                            if (intval($result['result']) != 1) {
                                $message = t('There was a problem sending notification email to PR for advert: @id.', [
                                    '@id' => $entity->id(),
                                ]);
                                Drupal::logger('PR Notification')
                                    ->error($message . ' Whole Error: ' . json_encode($result, TRUE));
                            } else {
                                $message = t('An email notification has been sent to PR for advert id: @id.', [
                                    '@id' => $entity->id(),
                                ]);
                                Drupal::logger('PR Notification')->notice($message);
                            }
                        }
                    }
                } else {
                    Drupal::logger('PR Notification')->notice(t('No PR\'s to notify about advert @id', array('@id' => $entity->id())));
                }
            }
        } elseif ($data->notificationType === Constants::PROPOSED_ADVERTS_TO_PR) {
            $pr = $data->pr;
            $adverts = $data->adverts;
            $key = Constants::PROPOSED_ADVERTS_TO_PR;
            $to = $pr->get('field_pr_email')->value;
            $reply = Drupal::config('system.site')->get('mail');
//            $params['cc'] = Drupal::config('system.site')->get('mail');
            $params['message'] = Markup::create(getEmailHtmlContent(Constants::PROPOSED_ADVERTS_TO_PR,
                $adverts, $pr->get('field_pr_first_name')->value));
            $langcode = Drupal::languageManager()->getDefaultLanguage()->getId();
            $send = TRUE;
            $result = $mailManager->mail($module, $key, $to, $langcode, $params, $reply, $send);
            if (intval($result['result']) != 1) {
                $message = t('There was a problem sending notification email to PR.');
                Drupal::logger('PR Notification')
                    ->error($message . ' Whole Error: ' . json_encode($result, TRUE));
            } else {
                $message = t('An email notification has been sent to PR.');
                Drupal::logger('PR Notification')->notice($message);
            }
        }
    }

}