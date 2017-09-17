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
use function fivestar_get_votes;

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
class RiRAdvertDetails extends BlockBase {

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
    public function build() {
        $node = Drupal::routeMatch()->getParameter('node');
        $advert = NULL;
        $advertiser = NULL;
        $ratings = '--';
        if (isset($node)) {
            $advert = Node::load($node->id());
            $referenceItem = $advert->get('field_advert_advertiser')->first();
            if (isset($referenceItem)){
              $entityReference = $referenceItem->get('entity');
              $entityAdapter = $entityReference->getTarget();
              $advertiser = $entityAdapter->getValue();


              $settings = array(
                'content_type' => 'node',
                'content_id' => $advertiser->id(),
                'entity' => $advertiser,
                'stars' => 5,
                'field_name' => 'field_agent_rating',
                'autosubmit' => TRUE,
                'allow_clear' => FALSE,
                'langcode' => Drupal::currentUser()->getPreferredLangcode(),
                'text' => 'none',
                'tag' => 'vote',
                'style' => 'average',
                'widget' => array( 'name' => 'oxygen', 'css' => drupal_get_path('module', 'fivestar') . '/widgets/oxygen/oxygen.css' )
              );

//              $fivestar_values = fivestar_get_votes('node', $advertiser->id());
//              //$render_form = drupal_get_form('fivestar_custom_widget', $fivestar_values, $settings);
//              $ratings = \Drupal::formBuilder()->getForm('Drupal\fivestar\Plugin\Field\FieldWidget', $fivestar_values, $settings);
            }
        }

        $output = [];
        $output[]['#cache']['max-age'] = 0; // No cache
        $output[] = ['#theme' => 'rir_advert_details', '#advert' => $advert, '#ratings' => $ratings];
        return $output;
    }
}