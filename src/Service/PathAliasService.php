<?php

namespace Drupal\rir_interface\Service;

use Drupal;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\node\Entity\Node;
use Drupal\path_alias\PathAliasInterface;

class PathAliasService {

  /**
   * @param \Drupal\path_alias\PathAliasInterface $pathAlias
   *
   * @return void
   */
  public function updateNodeRelativePath(PathAliasInterface $pathAlias): void {
    preg_match('/node\/(\d+)/', $pathAlias->getPath(), $matches);
    $node = Node::load($matches[1]);
    try {
      switch ($node->bundle()) {
        case 'advert':
          $node->set('field_advert_relative_path', $pathAlias->getAlias());
          $node->save();
          break;
        case 'agent':
          $node->set('field_agent_relative_path', $pathAlias->getAlias());
          $node->save();
          break;
        case 'property_request':
          $node->set('field_pr_relative_path', $pathAlias->getAlias());
          $node->save();
          break;
      }
    }
    catch (EntityStorageException $e) {
      Drupal::logger('rir_interface')->error($e->getMessage());
    }
  }
}