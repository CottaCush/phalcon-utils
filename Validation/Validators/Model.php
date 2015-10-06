<?php

namespace PhalconUtils\Validation\Validators;


use Phalcon\Mvc\Model as PhalconModel;
use Phalcon\Validation;
use Phalcon\Validation\Validator;
use Phalcon\Validation\ValidatorInterface;
use ReflectionClass;

/**
 * Class Model
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Validation\Validators
 */
class Model extends Validator implements ValidatorInterface
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
        $value = $validation->getValue($attribute);
        $model_name = $this->getOption('model', null);
        $conditions = $this->getOption('conditions', null);
        $bind = $this->getOption('bind', []);

        $model = new ReflectionClass($model_name);
        /** @var PhalconModel $model_instance */
        $model = $model->newInstanceWithoutConstructor();

        if (is_null($conditions)) {
            $data = $model::findFirst($value);
        } else {
            $data = $model::findFirst(['conditions' => $conditions, 'bind' => $bind]);
        }

        return ($data) ? true : false;
    }
}