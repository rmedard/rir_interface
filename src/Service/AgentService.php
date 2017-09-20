<?php

namespace Drupal\rir_interface\Service;

use Drupal;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\node\Entity\Node;

/**
 * Created by PhpStorm.
 * User: reberme
 * Date: 20/09/2017
 * Time: 13:00
 */

class AgentService {
    protected $entityTypeManager;

    public function __construct(EntityTypeManager $entityTypeManager) {
        $this->entityTypeManager = $entityTypeManager;
    }

    /**
     * @param $agent_id
     * @param int $status Status of the adverts. Defaults to PUBLISHED
     *
     * @return \Drupal\Core\Entity\EntityInterface[]|static[]
     */
    public function loadAdverts($agent_id, $status = Node::PUBLISHED){
        $query = Drupal::entityQuery('node')
          ->condition('type', 'advert')
          ->condition('status', $status)
          ->condition('field_advert_advertiser.target_id', $agent_id); // Could be field_advert_advertiser.entity.field_name (other than id)
        $advert_ids = $query->execute();
        return Node::loadMultiple($advert_ids);
    }

    public function validate_advert_agent($advert){
        $is_valid = TRUE;
        if ($advert->get('field_advert_is_agent')->value === 1){
            $agent_id = $advert->get('field_advert_advertiser')->target_id;
            if (isset($agent_id)){
                $status = $advert->get('field_advert_advertiser')->entity->get('status')->value;
                if ($status !== 1){
                    $is_valid = FALSE;
                }
            }
        } else {
            $advert->set('field_advert_advertiser', NULL);
            $advert->save();
            $is_valid = TRUE;
        }
        return $is_valid;
    }
}