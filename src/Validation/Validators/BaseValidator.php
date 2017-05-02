<?php

namespace PhalconUtils\Validation\Validators;

use Phalcon\Mvc\Model;
use Phalcon\Validation\Validator;
use PhalconUtils\Validation\BaseValidation;
use PhalconUtils\Validation\CustomMessage;
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
     */
    public function addMessageToValidation($validation, $default_message, $attribute, $type)
    {
        $message = $this->getOption('message');
        if (is_null($message)) {
            $message = $default_message;
        }

        if ($this->getOption('append_messages', true)) {
            $code = $this->getOption('code', null);
            $validation->appendMessage(new CustomMessage($message, $attribute, $type, $code));
        }
    }
}
