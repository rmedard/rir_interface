<?php
/**
 * Created by PhpStorm.
 * User: reberme
 * Date: 31/08/2017
 * Time: 15:52
 */

namespace Drupal\rir_interface\Plugin\Block;


use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;

/**
 * Class RiRAdvertDetails
 *
 * @package Drupal\rir_interface\Plugin\Block
 * @Block(
 *   id = "rir_advert_details",
 *   admin_label = @Translation("RiR Advert Details"),
 *   category = @Translation("Custom RIR Blocks")
 * )
 */
class RiRAdvertDetails extends BlockBase
{

    /**
     * Builds and returns the renderable array for this block plugin.
     *
     * If a block should not be rendered because it has no content, then this
     * method must also ensure to return no content: it must then only return
     * an
     * empty array, or an empty array with #cache set (with cacheability
     * metadata indicating the circumstances for it being empty).
     *
     * @return array
     *   A renderable array representing the content of the block.
     *
     * @see \Drupal\block\BlockViewBuilder
     */
    public function build()
    {
        $node = Drupal::routeMatch()->getParameter('node');
        $advert = NULL;
        $advertiser = NULL;
        $output = [];
        $output[]['#cache']['max-age'] = 0; // No cache
        if ($node and $node instanceof NodeInterface and $node->bundle() == 'advert') {
            $output[] = ['#theme' => 'rir_advert_details', '#advert' => $node];
        }
        return $output;
    }
}