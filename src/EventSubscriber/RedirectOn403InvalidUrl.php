<?php

namespace Drupal\rir_interface\EventSubscriber;


use Drupal;
use Drupal\Core\EventSubscriber\HttpExceptionSubscriberBase;
use Drupal\Core\Routing\RequestHelper;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class RedirectOn403InvalidUrl extends HttpExceptionSubscriberBase
{

    protected function getHandledFormats(): array
    {
        return [];
    }

    public function on403(GetResponseForExceptionEvent $event) {
        Drupal::logger('rir_interface')->debug($event->getRequest()->getRequestUri());
        if (!RequestHelper::isCleanUrl($event->getRequest())) {
            Drupal::logger('rir_interface')->warning('403 with invalid Url with /index.php detected.');
            $cleanRequestUri = $this->cleanPath($event->getRequest()->getRequestUri());
            $response = new TrustedRedirectResponse($cleanRequestUri, 302);
            $response->headers->set('X-Drupal-Route-Normalizer', 1);
            $event->setResponse($response);
        }
    }

    private function cleanPath($pathStr): string {
        if (substr($pathStr, 0, 10) === '/index.php') {
            return $this->cleanPath(substr_replace($pathStr, '', 0, 10));
        }
        return $pathStr;
    }
}