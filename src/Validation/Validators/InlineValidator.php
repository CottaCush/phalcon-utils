<?php

namespace PhalconUtils\Validation\Validators;

use PhalconUtils\Exceptions\ValidatorException;

/**
 * Class InlineValidator
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Validation\Validators
 * @desc supply a closure that returns a boolean as  the `function` option
 */
class InlineValidator extends BaseValidator
{
    /**
     * Executes the validation
     * @param mixed $validation
     * @param string $attribute
     * @return bool
     * @throws ValidatorException
     */
    public function validate(\Phalcon\Validation $validation, $attribute)
    {
        $function = $this->getOption('function');

        if (!($function instanceof \Closure)) {
            throw new ValidatorException('Option function must be a closure');
        }

        $response = call_user_func($function, $validation);

        if (is_string($response)) {
            $this->setOption('message', null);
            $message = $response;
        } else {
            $message = 'Invalid :field supplied';
        }

        if ($response !== true) {
            $this->addMessageToValidation($validation, $message, $attribute, '');
            return false;
        }

        return true;
    }
}
