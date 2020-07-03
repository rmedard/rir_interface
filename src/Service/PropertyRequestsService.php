<?php
/**
 * Created by PhpStorm.
 * User: medar
 * Date: 07/02/2019
 * Time: 01:27
 */

namespace Drupal\rir_interface\Service;


use Drupal;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\node\NodeInterface;

class PropertyRequestsService
{
    protected $entityTypeManager;

    /**
     * PropertyRequestsService constructor.
     * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
     */
    public function __construct(\Drupal\Core\Entity\EntityTypeManager $entityTypeManager)
    {
        $this->entityTypeManager = $entityTypeManager;
    }

    public function loadPRsForAdvert($advert) {
        if ($advert instanceof NodeInterface) {
            try {
                $storage = $this->entityTypeManager->getStorage('node');
                return $storage
                    ->loadByProperties(array('type' => 'property_request', 'field_pr_proposed_properties' => $advert->id()));
            } catch (InvalidPluginDefinitionException $e) {
                Drupal::logger('rir_interface')->error('Invalid plugin: ' . $e->getMessage());
            } catch (PluginNotFoundException $e) {
                Drupal::logger('rir_interface')->error('Plugin not found: ' . $e->getMessage());
            }
        }
        return array();
    }
}