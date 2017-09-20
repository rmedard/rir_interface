<?php
/**
 * Created by PhpStorm.
 * User: medard
 * Date: 20.09.17
 * Time: 21:13
 */

namespace Drupal\rir_interface\Plugin\Validation\Constraint;


use Drupal\node\Entity\Node;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PublishedAgentValidator extends ConstraintValidator {

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint) {
        if (!$this->isAgentPublished($value)){
            $this->context->addViolation($constraint->not_published, ['%value' => $value]);
        }
    }

    private function isAgentPublished($value){
        $agent = Node::load($value);
        return $agent->isPublished();
    }
}