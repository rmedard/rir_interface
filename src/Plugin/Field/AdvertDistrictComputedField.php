<?php

namespace Drupal\rir_interface\Plugin\Field;

use Drupal;
use Drupal\Core\Entity\Plugin\DataType\EntityAdapter;
use Drupal\Core\Field\FieldItemList;
use Drupal\Core\TypedData\ComputedItemListTrait;
use Drupal\node\NodeInterface;
use Drupal\taxonomy\TermInterface;
use Drupal\taxonomy\TermStorageInterface;

class AdvertDistrictComputedField extends FieldItemList
{
  use ComputedItemListTrait;

  protected function computeValue()
  {
    $district = '';
    $adaptor = $this->parent;
    if ($adaptor instanceof EntityAdapter) {
      $advert = $adaptor->getEntity();
      if ($advert instanceof NodeInterface) {
        $locality = $advert->get('field_advert_locality')->entity;
        if ($locality instanceof TermInterface) {
          $termStorage = Drupal::entityTypeManager()->getStorage('taxonomy_term');
          if ($termStorage instanceof TermStorageInterface) {
            $parents = $termStorage->loadAllParents($locality->id());
            if (count($parents) > 2) {
              $district = array_values($parents)[1]->getName();
            } elseif (count($parents) === 2) {
              $district = array_values($parents)[0]->getName();
            }
          }
        }
      }
    }
    $this->list[0] = $this->createItem(0, $district);
  }
}
