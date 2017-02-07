<?php

namespace Kanboard\Plugin\Timetrackingeditor\Validator;

use SimpleValidator\Validator;
use SimpleValidator\Validators;
use Kanboard\Validator\BaseValidator;

/**
 * SubtaskTimetracking Validator
 *
 * @package  Kanboard\Plugin\Timetrackingeditor\Validator
 * @author   Thomas Stinner
 */
class SubtaskTimeTrackingValidator extends BaseValidator
{
    /**
     * Validate creation
     *
     * @access public
     * @param  array   $values           Form values
     * @return array   $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function validateCreation(array $values)
    {
        $rules = array ();
            

        $v = new Validator($values, array_merge($rules, $this->commonValidationRules()));

        return array(
            $v->execute(),
            $v->getErrors()
        );
    }

    /**
     * Validate modification
     *
     * @access public
     * @param  array   $values           Form values
     * @return array   $valid, $errors   [0] = Success or not, [1] = List of errors
     */
    public function validateModification(array $values)
    {
        $rules = array (
            new Validators\Required('id', t('The Timetracking id is required'))
        );

        $v = new Validator($values, array_merge($rules, $this->commonValidationRules()));

        return array(
            $v->execute(),
            $v->getErrors()
        );
    }

    /**
    * Common validation rules, valid for creation and modification
    *
    * @access private
    */
    private function commonValidationRules()
    {
        $rules = array(
            new Validators\Required('task_id', t('The Task id is required')),
            new Validators\Required('opposite_subtask_id', t('The subtask is required')),
            new Validators\Required('start', t('The Start Date is required')),
            new Validators\Required('time_spent', t('The Time spent is required')),
        );
        return $rules;
    }
}
