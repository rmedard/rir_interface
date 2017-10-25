<?php
/**
 * Created by PhpStorm.
 * User: medar
 * Date: 25/10/2017
 * Time: 23:13
 */

namespace Drupal\rir_interface\Plugin\Block;


use Drupal\Core\Block\BlockBase;

/**
 * Class HiRGoogleAdsense
 * @package Drupal\rir_interface\Plugin\Block
 * @Block(
 *   id = "hir_google_adsense",
 *   admin_label = @Translation("HiR Google AdSense Block"),
 *   category = @Translation("Custom RIR Blocks")
 * )
 */
class HiRGoogleAdsense extends BlockBase {

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
    public function build()
    {
        return[
            '#theme' => 'hir_google_adsense',
        ];
    }
}