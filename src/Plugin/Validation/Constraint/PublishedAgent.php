<?php
/**
 * Created by PhpStorm.
 * User: medard
 * Date: 20.09.17
 * Time: 21:10
 */

namespace Drupal\rir_interface\Plugin\Validation\Constraint;

use Drupal\Core\Validation\Annotation\Constraint;

/**
 * Class PublishedAgent
 *
 * @package Drupal\rir_interface\Plugin\Validation\Constraint
 * @Constraint(
 *   id = "published_agent",
 *   label = @Translation("Published ", context = "Validation"),
 * )
 */
class PublishedAgent extends Constraint {

    public $not_published = '%value is not published';
}