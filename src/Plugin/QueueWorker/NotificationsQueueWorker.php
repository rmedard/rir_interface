<?php
/**
 * Created by PhpStorm.
 * User: reberme
 * Date: 19/01/2018
 * Time: 10:09
 */

namespace Drupal\rir_interface\Plugin\QueueWorker;


use Drupal;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\Core\Render\Markup;
use Drupal\node\NodeInterface;
use Drupal\rir_interface\Utils\Constants;

/**
 * Class NotificationsQueueWorker
 *
 * @package Drupal\rir_interface\Plugin\QueueWorker
 * @QueueWorker(
 *  id = "notifications_sender",
 *  title = @Translation("Notifications Queue Worker"),
 *  cron = {"time" = 60}
 * )
 */
class NotificationsQueueWorker extends QueueWorkerBase {

		/**
		 * Works on a single queue item.
		 *
		 * @param mixed $data
		 *   The data that was passed to
		 *   \Drupal\Core\Queue\QueueInterface::createItem() when the item was queued.
		 *
		 * @throws \Drupal\Core\Queue\RequeueException
		 *   Processing is not yet finished. This will allow another process to claim
		 *   the item immediately.
		 * @throws \Exception
		 *   A QueueWorker plugin may throw an exception to indicate there was a
		 *   problem. The cron process will log the exception, and leave the item in
		 *   the queue to be processed again later.
		 * @throws \Drupal\Core\Queue\SuspendQueueException
		 *   More specifically, a SuspendQueueException should be thrown when a
		 *   QueueWorker plugin is aware that the problem will affect all subsequent
		 *   workers of its queue. For example, a callback that makes HTTP requests
		 *   may find that the remote server is not responding. The cron process will
		 *   behave as with a normal Exception, and in addition will not attempt to
		 *   process further items from the current item's queue during the current
		 *   cron run.
		 *
		 * @see \Drupal\Core\Cron::processQueues()
		 */
		public function processItem($data) {

				if ($data->notificationType === Constants::ADVERT_VALIDATED){
						$entity = $data->entity;
						if ($entity instanceof NodeInterface){
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
										$message = t('There was a problem sending alert email to @email for creating advert id: @id.', [
											'@email' => $to,
											'@id' => $entity->id(),
										]);
										Drupal::logger('rir_interface')
											->error($message . ' Whole Error: ' . json_encode($result, TRUE));
								} else {
										$message = t('An email notification has been sent to @email for creating advert id: @id.', [
											'@email' => $to,
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