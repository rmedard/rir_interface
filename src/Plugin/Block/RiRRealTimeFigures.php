<?php
/**
 * Created by PhpStorm.
 * User: medard
 * Date: 30.07.17
 * Time: 16:11
 */

namespace Drupal\rir_interface\Plugin\Block;


use Drupal\Core\Block\BlockBase;

/**
 * Class RiRRealTimeFigures
 *
 * @package Drupal\rir_interface\Plugin\Block
 * @Block(
 *   id = "rir_realtime_block",
 *   admin_label = @Translation("RiR Realtime Figures Block"),
 *   category = @Translation("Custom RIR Blocks")
 * )
 */
class RiRRealTimeFigures extends BlockBase {

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
    return [
      '#theme' => 'rir_realtime',
    ];
  }
}