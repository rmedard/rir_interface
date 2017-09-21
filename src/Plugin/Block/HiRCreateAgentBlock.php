<?php
/**
 * Created by PhpStorm.
 * User: reberme
 * Date: 21/09/2017
 * Time: 11:09
 */

namespace Drupal\rir_interface\Plugin\Block;


use Drupal\Core\Block\BlockBase;

/**
 * Class HiRCreateAgentBlock
 *
 * @package Drupal\rir_interface\Plugin\Block
 * @Block(
 *   id = "hir_create_agent_block",
 *   admin_label = @Translation("HiR Create Agent Block"),
 *   category = @Translation("Custom RIR Blocks")
 * )
 */
class HiRCreateAgentBlock extends BlockBase {

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
        return[
          '#theme' => 'hir_create_agent'
        ];
    }
}