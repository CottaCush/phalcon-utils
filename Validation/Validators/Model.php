<?php

namespace PhalconUtils\Validation\Validators;


use Phalcon\Mvc\Model as PhalconModel;
use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;
use Phalcon\Validation\ValidatorInterface;

/**
 * Class Model
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Validation\Validators
 */
class Model extends BaseValidator implements ValidatorInterface
{

    /**
     * Executes the validation
     * Note: Explicitly supply `conditions` and `bind` options if model PK is not `id`
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
        $show_messages = $this->getOption('show_messages', true);

        /** @var \Phalcon\Mvc\Model $model */
        $model = $this->getModel($model_name);

        if (is_null($conditions)) {
            $data = $model::findFirst(["id = ?0",
                "bind" => $value]);
        } else {
            $data = $model::findFirst(['conditions' => $conditions, 'bind' => $bind]);
        }
        if (!$data) {
            if ($show_messages) {
                $this->addMessageToValidation($validation, 'Invalid :field supplied', $attribute, 'Model');
            }
            return false;
        }
        return true;
    }
}