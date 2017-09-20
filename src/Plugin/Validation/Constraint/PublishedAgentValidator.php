<?php
/**
 * Created by PhpStorm.
 * User: medard
 * Date: 20.09.17
 * Time: 21:13
 */

namespace Drupal\rir_interface\Plugin\Validation\Constraint;


use Drupal;
use Drupal\node\Entity\Node;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PublishedAgentValidator extends ConstraintValidator {

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $values
     * @param Constraint $constraint The constraint for the validation
     *
     * @internal param mixed $value The value that should be validated
     */
    public function validate($values, Constraint $constraint) {
        foreach ($values as $value){
            Drupal::logger('rir_interface')->debug('Value: ' . $value);
            if (!$this->isAgentPublished($value->value)){
                $this->context->addViolation($constraint->not_published, ['%value' => $value]);
            }
        }
    }

    private function isAgentPublished($value){
        $agent = Node::load($value);
        Drupal::logger('rir_interface')->debug('Validated value: ' . $value);
        if (isset($agent)){
            return $agent->isPublished();
        }
        return TRUE;
    }
}