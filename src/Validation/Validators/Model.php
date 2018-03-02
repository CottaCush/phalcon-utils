<?php

namespace PhalconUtils\Validation\Validators;


use Phalcon\Validation;
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
        $modelName = $this->getOption('model', null);
        $conditions = $this->getOption('conditions', null);
        $bind = $this->getOption('bind', []);
        $showMessages = $this->getOption('show_messages', true);
        $compareColumn = $this->getOption('compare_column', null);
        $compareWithSensitivity = $this->getOption('compare_with_sensitivity', true);

        /** @var \Phalcon\Mvc\Model $model */
        $model = $this->getModel($modelName);

        if (is_null($conditions)) {
            if (is_null($value)) {
                return false;
            }
            $data = $model::findFirst(["id = ?0", "bind" => $value]);
        } else {
            $data = $model::findFirst(['conditions' => $conditions, 'bind' => $bind]);
        }
        if (!$data) {
            if ($showMessages) {
                $this->addMessageToValidation($validation, 'Invalid :field supplied', $attribute, 'Model');
            }
            return false;
        }

        $value = ($compareWithSensitivity) ? $value : strtolower($value);
        $modelValue = ($compareWithSensitivity) ? $model->{$compareColumn} : strtolower($model->{$compareColumn});
        if ($compareColumn && $modelValue != $value) {
            return false;
        }

        return true;
    }
}