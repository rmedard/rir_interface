<?php
/**
 * Created by PhpStorm.
 * User: medar
 * Date: 17/06/2018
 * Time: 21:00
 */

namespace Drupal\rir_interface\Service;


use Drupal;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;

class AdvertsService
{

    protected $entityTypeManager;

    /**
     * AdvertsService constructor.
     * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
     */
    public function __construct(EntityTypeManager $entityTypeManager)
    {
        $this->entityTypeManager = $entityTypeManager;
    }

    public function loadSimilarAdverts(NodeInterface $advert_node) {
        $adverts = array();
        try {
            $storage = $this->entityTypeManager->getStorage('node');

//                ->condition('field_advert_district.target_id', $advert_node->get('field_advert_district.target_id'))
            $query = $storage->getQuery()
                ->condition('type', 'advert')
                ->condition('status', Node::PUBLISHED)
                ->condition('nid', $advert_node->id(), '<>');
//                ->condition('field_advert_type', $advert_node->get('field_advert_type')->value);
            $advertsIds = $query->execute();
            Drupal::logger('rir_interface')->debug('Ids: ' . implode('|', $advertsIds) . ' Current ID: ' . $advert_node->id());
            $adverts = $storage->loadMultiple($advertsIds);
        } catch (InvalidPluginDefinitionException $e) {
            Drupal::logger('rir_interface')->error('Invalid plugin: ' . $e->getMessage());
        } catch (PluginNotFoundException $e) {
            Drupal::logger('rir_interface')->error('Plugin not found: ' . $e->getMessage());
        }
        return $adverts;
    }

}