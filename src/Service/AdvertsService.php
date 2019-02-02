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

    public function loadSimilarAdverts(NodeInterface $advert_node)
    {
        $adverts = array();
        try {
            $storage = $this->entityTypeManager->getStorage('node');

            $query = $storage->getQuery()->range(0, 5)
                ->condition('type', 'advert')
                ->condition('status', Node::PUBLISHED)
                ->condition('nid', $advert_node->id(), '<>')
                ->condition('field_advert_type', $advert_node->get('field_advert_type')->value)
                ->condition('field_advert_district.target_id', $advert_node->get('field_advert_district')->target_id);

            if ($advert_node->get('field_advert_type')->value != 'auction' and
                $advert_node->get('field_advert_price_negociable')->value == '0') {
                $price = intval($advert_node->get('field_price_in_rwf')->value);
                $min_price = intval($price - ($price * 0.1));
                $max_price = intval($price + ($price * 0.1));
                $query = $query->condition('field_price_in_rwf', array($min_price, $max_price), 'BETWEEN');
            }
            $advertsIds = $query->execute();

            if ($advertsIds && !empty($advertsIds)) {
                $advertsIds = array_diff($advertsIds, [$advert_node->id()]);
                $adverts = $storage->loadMultiple($advertsIds);
            }
        } catch (InvalidPluginDefinitionException $e) {
            Drupal::logger('rir_interface')->error('Invalid plugin: ' . $e->getMessage());
        } catch (PluginNotFoundException $e) {
            Drupal::logger('rir_interface')->error('Plugin not found: ' . $e->getMessage());
        }
        return $adverts;
    }

}