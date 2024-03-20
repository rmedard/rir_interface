<?php

namespace Drupal\rir_interface\Plugin\Field;

use Drupal;
use Drupal\Core\Entity\Plugin\DataType\EntityAdapter;
use Drupal\Core\Field\Plugin\Field\FieldType\IntegerItem;
use Drupal\Core\TypedData\ComputedItemListTrait;
use Drupal\node\NodeInterface;

class ActiveAgentJobsCountComputedField extends IntegerItem {

  use ComputedItemListTrait;

  /**
   * @throws \Drupal\Core\TypedData\Exception\ReadOnlyException
   */
  protected function computeValue(): void {
    $advertsCount = 0;
    $adaptor = $this->parent;
    if ($adaptor instanceof EntityAdapter) {
      $agent = $adaptor->getEntity();
      if ($agent instanceof NodeInterface) {
        $advertsCount = Drupal::entityQuery('node')
          ->accessCheck(FALSE)
          ->condition('type', 'advert')
          ->condition('status', NodeInterface::PUBLISHED)
          ->condition('field_advert_advertiser.target_id', $agent->id())
          ->count()
          ->execute();
      }
    }
    $this->setValue($advertsCount);
  }
}