<?php

namespace PhalconUtils\Validation\Validators;

use Phalcon\Validation;

/**
 * Class NotExisting
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Validation\Validators
 */
class NotExisting extends Model
{

    /**
     * Executes the validation
     *
     * @param mixed $validation
     * @param string $attribute
     * @return bool
     */
    public function validate(Validation $validation, $attribute)
    {
        $this->setOption('show_messages', false);
        $is_valid = !parent::validate($validation, $attribute);
        if (!$is_valid) {
            $this->addMessageToValidation($validation, ':field already exists', $attribute, 'NotExisting');
        }
        return $is_valid;
    }
}
