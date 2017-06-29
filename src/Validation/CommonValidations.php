<?php

namespace PhalconUtils\Validation;

use Phalcon\Validation\Validator\PresenceOf;
use PhalconUtils\Util\ArrayUtils;
use PhalconUtils\Validation\Validators\InlineValidator;
use PhalconUtils\Validation\Validators\Model;

/**
 * Class CommonValidations
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Validation
 */
trait CommonValidations
{
    /**
     * Validates that a value is an object
     * <code>
     *  $data = ['object' => $object, 'array' => (array)$object];
     *  $this->addIsObjectValidation('object');
     * </code>
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $field
     * @param bool $cancelOnFail
     */
    public function addIsObjectValidation($field, $cancelOnFail = true)
    {
        if (!is_null($this->getValue($field))) {
            $this->add($field, new InlineValidator([
                'function' => function () use ($field) {
                    return is_object($this->getValue($field));
                },
                'message' => sprintf(ValidationMessages::PARAMETER_MUST_BE_AN_OBJECT, $field),
                'cancelOnFail' => $cancelOnFail
            ]));
        }
    }

    /**
     * Validates that a value is an array
     * <code>
     *  $data = ['object' => $object, 'array' => (array)$object];
     *  $this->addIsArrayValidation('array');
     * </code>
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $field
     * @param bool $cancelOnFail
     */
    public function addIsArrayValidation($field, $cancelOnFail = true)
    {
        if (!is_null($this->getValue($field))) {
            $this->add($field, new InlineValidator([
                'function' => function () use ($field) {
                    return is_array($this->getValue($field));
                },
                'message' => sprintf(ValidationMessages::PARAMETER_MUST_BE_AN_ARRAY, $field),
                'cancelOnFail' => $cancelOnFail
            ]));
        }
    }

    /**
     * Validate bulk model fields
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $field
     * @param $modelClass
     * @param string $bulkField
     * @param string $column
     */
    public function validateBulkModelField($field, $modelClass, $bulkField, $column = 'id')
    {
        $this->validateValidity(
            $field,
            function ($uniqueAdvertIds) use ($modelClass, $column) {
                $uniqueAdverts = $modelClass::query()->inWhere($column, $uniqueAdvertIds)->execute();
                return count($uniqueAdvertIds) == $uniqueAdverts->count();
            },
            $bulkField
        );
    }

    /**
     * Validates validity of field in bulk data using a function
     * <code>
     * $integer1Object = new \stdClass();
     * $integer1Object->number = 1;
     * $integer2Object = new \stdClass();
     * $integer2Object->number = 2;
     * $data = [
     *      'integers_and_strings' => [1, 2, 'one', 'two'],
     *      'integers' => [1, 2, 1, 2],
     *      'associative_integers' => [
     *      $integer1Object,
     *      $integer2Object,
     *      $integer1Object,
     *      $integer2Object
     * ]
     * ];
     *
     * $validationFunction = function ($integers) {
     * foreach ($integers as $integer) {
     * if (!is_int($integer)) {
     * return '"' . $integer . '" is not an integer';
     * }
     * }
     * return true;
     * };
     * //for non-associative arrays
     * $this->validateValidity(null, $validationFunction, 'integers_and_strings');
     *
     * //for associative arrays
     * $this->validateValidity('number', $validationFunction, 'associative_integers');
     * </code>
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $field
     * @param $validateFunction
     * @param string $bulkField
     */
    public function validateValidity($field, $validateFunction, $bulkField)
    {
        if (is_array($this->getValue($bulkField))) {
            $uniqueElements = ArrayUtils::getUniqueColumnElements($this->getValue($bulkField), $field);
            if (!empty($uniqueElements)) {
                $this->add($bulkField, new InlineValidator([
                    'function' => function () use ($uniqueElements, $validateFunction) {
                        return call_user_func($validateFunction, $uniqueElements);
                    },
                    'message' => sprintf(
                        ValidationMessages::PARAMETER_CONTAINS_INVALID_FIELD,
                        (is_null($field) ? $bulkField : $field)
                    ),
                    'cancelOnFail' => true
                ]));
            }
        }
    }

    /**
     * Validates that an array has a set of fields
     * <code>
     *  $data = [
     *      'numbers' => [
     *          [
     *              'one' => 1,
     *              'two' => 2,
     *              'three' => 3
     *          ]
     *      ]
     * ];
     *  $this->validateArrayElementsHasFields('numbers', ['one', 'two', 'three']);
     * </code>
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $field
     * @param $requiredElements
     */
    public function validateArrayElementsHasFields($field, array $requiredElements)
    {
        $fieldValue = $this->getValue($field);
        if (!is_array($fieldValue)) {
            return;
        }

        $this->add($field, new InlineValidator([
            'function' => function () use ($fieldValue, $requiredElements, $field) {
                foreach ($fieldValue as $element) {
                    foreach ($requiredElements as $requiredElement) {
                        $validation = new RequestValidation($element);
                        $validation->add($requiredElement, new PresenceOf(['allowEmpty' => false]));
                        if (!$validation->validate()) {
                            return $validation->getMessages() . ' as part of a ' . $field . ' array';
                        }
                    }
                }
                return true;
            },
            'message' => sprintf(ValidationMessages::INCORRECT_ELEMENT_STRUCTURE, $field),
            'cancelOnFail' => true
        ]));
    }

    /**
     * Validates fields of an array with a custom validation
     * <code>
     *  $data = [
     *      'numbers' => [
     *          [
     *              'one' => 1,
     *              'two' => 2,
     *              'three' => 3
     *          ]
     *      ]
     * ];
     *  $this->validateArrayElements(
     *            'numbers',
     *        ['one' => new InlineValidator([
     *              'function' => function ($validation) {
     *                  return $validation->getValue('one') === 1;
     *              },
     *              'message' => 'one must have the integer 1'
     *         ])]
     * );
     * </code>
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $field
     * @param $requiredElements
     */
    public function validateArrayElements($field, array $requiredElements)
    {
        $fieldValue = $this->getValue($field);
        if (!is_array($fieldValue)) {
            return;
        }

        $this->add($field, new InlineValidator([
            'function' => function () use ($fieldValue, $requiredElements) {
                foreach ($fieldValue as $element) {
                    foreach ($requiredElements as $requiredElement => $validations) {
                        $requestValidation = new RequestValidation($element);

                        if (!is_array($validations)) {
                            $validations = [$validations];
                        }

                        foreach ($validations as $validation) {
                            $requestValidation->add($requiredElement, $validation);
                        }

                        if (!$requestValidation->validate()) {
                            return $requestValidation->getMessages();
                        }
                    }
                }
                return true;
            },
            'message' => sprintf(ValidationMessages::INCORRECT_ELEMENT_STRUCTURE, $field),
            'cancelOnFail' => true
        ]));
    }

    /**
     * Validates fields of an array with a custom validation
     * <code>
     * $data = [
     *      'empty_array' => [],
     *      'filled_array' => ['number' => 1],
     *      'empty_object' => new \stdClass(),
     *      'filled_object' => $filledObject
     * ];
     *
     * $this->isNotEmpty('empty_array');
     * </code>
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $field
     */
    public function isNotEmpty($field)
    {
        $this->add($field, new InlineValidator([
            'function' => function () use ($field) {
                $value = $this->getValue($field);
                if (is_array($value) && count($value) != 0) {
                    return true;
                }

                if (is_object($value)) {
                    $value = (array)$value;
                    return count($value) != 0;
                }

                return !empty($this->getValue($field));
            },
            'message' => $field . ' is empty'
        ]));
    }

    /**
     * Validates boolean value
     * <code>
     * $data = [
     *     'boolean_true' => true,
     *     'boolean_false' => false,
     *     'string_true' => 'true',
     *     'string_false' => 'false',
     *     'integer_one' => 1,
     *     'integer_zero' => 0,
     *     'string_zero' => '0',
     *     'string_one' => '1',
     *     'random_string' => 'string',
     *     'random_integer' => '123123',
     * ];
     *
     * $this->validateBooleanValue('boolean_true');
     * </code>
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $field
     * @param bool $cancelOnFail
     */
    public function validateBooleanValue($field, $cancelOnFail = true)
    {
        $this->add($field, new InlineValidator([
            'function' => function () use ($field) {
                return in_array($this->getValue($field), [0, 1, '0', '1', true, false], true);
            },
            'message' => sprintf(ValidationMessages::PARAMETER_MUST_BE_BOOLEAN, $field),
            'cancelOnFail' => $cancelOnFail
        ]));
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $field
     * @param $model
     * @param string $column
     */
    public function validateDataField($field, $model, $column = 'key')
    {
        $this->add($field, new Model([
            'model' => $model,
            'conditions' => $column . ' = :key:',
            'bind' => ['key' => $this->getValue($field)],
            'message' => sprintf(ValidationMessages::INVALID_PARAMETER_SUPPLIED, $field)
        ]));
    }
}
