<?php

namespace PhalconUtils\Validation\Validators;

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;
use PhalconUtils\Validation\BaseValidation;
use ReflectionClass;

/**
 * Class BaseValidator
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Validation\Validators
 */
abstract class BaseValidator extends Validator
{

    public function getModel($model_name)
    {
        $model = new ReflectionClass($model_name);
        /** @var Model $model_instance */
        $model = $model->newInstanceWithoutConstructor();
        return $model;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $validation BaseValidation
     * @param $default_message
     * @param $attribute
     * @param $type
     * @return bool
     */
    public function addMessageToValidation($validation, $default_message, $attribute, $type)
    {
        $message = $this->getOption('message');
        if (is_null($message)) {
            $message = $default_message;
        }

        $validation->appendMessage(new Message($message, $attribute, $type));
        return false;
    }
}