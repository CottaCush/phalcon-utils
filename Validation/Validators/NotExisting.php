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
        return !parent::validate($validation, $attribute);
    }
}