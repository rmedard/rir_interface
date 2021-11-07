<?php


namespace Drupal\rir_interface\Plugin\Block;


use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Fax' block.
 *
 * @Block(
 *   id = "geolocation_google_places_api_attribution_block",
 *   admin_label = @Translation("Geolocation - Google Places API Attribution block"),
 * )
 */
class GooglePlacesApiAttributionBlock extends BlockBase
{

    /**
     * {@inheritdoc}
     */
    public function build(): array
    {
        return ['#markup' => '<span id="geolocation-google-places-api-attribution"></span>'];
    }
}
