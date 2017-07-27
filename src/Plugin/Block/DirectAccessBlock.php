<?php
/**
 * Created by PhpStorm.
 * User: medard
 * Date: 27.07.17
 * Time: 22:13
 */

namespace Drupal\rir_interface\Plugin\Block;


use Drupal\Core\Block\BlockBase;

class DirectAccessBlock extends BlockBase {

  /**
   * Builds and returns the renderable array for this block plugin.
   *
   * If a block should not be rendered because it has no content, then this
   * method must also ensure to return no content: it must then only return an
   * empty array, or an empty array with #cache set (with cacheability metadata
   * indicating the circumstances for it being empty).
   *
   * @return array
   *   A renderable array representing the content of the block.
   *
   * @see \Drupal\block\BlockViewBuilder
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\rir_interface\Form\DirectAccessForm');
    return $form;
  }
}