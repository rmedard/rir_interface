<?php
/**
 * Created by PhpStorm.
 * User: medard
 * Date: 20.09.17
 * Time: 21:13
 */

namespace Drupal\rir_interface\Plugin\Validation\Constraint;


use Drupal;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PublishedAgentValidator extends ConstraintValidator {

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $items
     * @param Constraint $constraint The constraint for the validation
     *
     * @internal param mixed $value The value that should be validated
     */
    public function validate($items, Constraint $constraint) {
        foreach ($items as $item){
            if ($item->entity->get('status')->value === 0){
                $this->context->addViolation($constraint->not_published, ['%value' => $item->entity->getTitle()]);
                Drupal::logger('rir_interface')->debug('Advert publication failed: ' . $item->entity->getTitle());
            }
        }
    }

}