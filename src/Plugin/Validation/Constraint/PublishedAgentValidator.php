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
     * @param mixed $items
     * @param Constraint $constraint The constraint for the validation
     *
     * @internal param mixed $value The value that should be validated
     */
    public function validate($items, Constraint $constraint) {
        foreach ($items as $item){
            Drupal::logger('rir_interface')->debug('Value: ' . $item->target_id);
            if (!$this->isAgentPublished($item->target_id)){
                $this->context->addViolation($constraint->not_published, ['%value' => $item->target_id]);
            }
        }
    }

    private function isAgentPublished($value){
        $agent = Node::load($value);
        if (isset($agent)){
            Drupal::logger('rir_interface')->debug('Validated value: ' . $agent->getTitle() . ' IsPublished: ' . $agent->get('status')->value);
            return $agent->isPublished();
        }
        return TRUE;
    }
}