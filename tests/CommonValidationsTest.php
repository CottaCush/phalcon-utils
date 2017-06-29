<?php

namespace Tests;

use Phalcon\Test\UnitTestCase;
use Phalcon\Validation\Validator\PresenceOf;
use PhalconUtils\Validation\Validators\InlineValidator;
use Tests\Stubs\CommonValidationsImpl;

/**
 * Class CommonValidationsTest
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package Tests
 */
class CommonValidationsTest extends UnitTestCase
{
    /** @var  CommonValidationsImpl */
    protected $commonValidationsObj;

    public function setUp()
    {
        $this->commonValidationsObj = new CommonValidationsImpl();
        parent::setUp();
    }

    public function testIsObjectValidation()
    {
        $object = new \stdClass();
        $object->number = 1;
        $data = ['object' => $object, 'array' => (array)$object];

        $this->commonValidationsObj->setData($data);

        $this->commonValidationsObj->addIsObjectValidation('object');
        $this->assertTrue($this->commonValidationsObj->validate());

        $this->commonValidationsObj->reset();
        $this->commonValidationsObj->addIsObjectValidation('array');
        $this->assertFalse($this->commonValidationsObj->validate());
    }

    public function testIsArrayValidation()
    {
        $object = new \stdClass();
        $object->number = 1;
        $data = ['object' => $object, 'array' => (array)$object];

        $this->commonValidationsObj->setData($data);

        $this->commonValidationsObj->addIsArrayValidation('object');
        $this->assertFalse($this->commonValidationsObj->validate());

        $this->commonValidationsObj->reset();
        $this->commonValidationsObj->addIsArrayValidation('array');
        $this->assertTrue($this->commonValidationsObj->validate());
    }

    public function testValidateValidity()
    {
        $integer1Object = new \stdClass();
        $integer1Object->number = 1;
        $integer2Object = new \stdClass();
        $integer2Object->number = 2;

        $data = [
            'integers_and_strings' => [1, 2, 'one', 'two'],
            'integers' => [1, 2, 1, 2],
            'associative_integers' => [
                $integer1Object,
                $integer2Object,
                $integer1Object,
                $integer2Object
            ]
        ];

        $validationFunction = function ($integers) {
            foreach ($integers as $integer) {
                if (!is_int($integer)) {
                    return '"' . $integer . '" is not an integer';
                }
            }
            return true;
        };

        $this->commonValidationsObj->setData($data);
        $this->commonValidationsObj->validateValidity(null, $validationFunction, 'integers_and_strings');
        $this->assertFalse($this->commonValidationsObj->validate());
        $this->assertEquals($this->commonValidationsObj->getMessages(), '"one" is not an integer');

        $this->commonValidationsObj->reset();
        $this->commonValidationsObj->validateValidity(null, $validationFunction, 'integers');
        $this->assertTrue($this->commonValidationsObj->validate());
        $this->assertEquals('', $this->commonValidationsObj->getMessages());

        $this->commonValidationsObj->reset();
        $this->commonValidationsObj->validateValidity('number', $validationFunction, 'associative_integers');
        $this->assertTrue($this->commonValidationsObj->validate());
        $this->assertEquals('', $this->commonValidationsObj->getMessages());
    }

    public function testValidateArrayElementsHasFields()
    {
        $data = [
            'numbers' => [
                [
                    'one' => 1,
                    'two' => 2,
                    'three' => 3
                ]
            ]
        ];
        $this->commonValidationsObj->setData($data);
        $this->commonValidationsObj->validateArrayElementsHasFields('numbers', ['one', 'two', 'three']);
        $this->assertTrue($this->commonValidationsObj->validate());

        $this->commonValidationsObj->reset();
        $this->commonValidationsObj->validateArrayElementsHasFields('numbers', ['one', 'four']);
        $this->assertFalse($this->commonValidationsObj->validate());
        $this->assertEquals(
            'Field four is required as part of a numbers array',
            $this->commonValidationsObj->getMessages()
        );
    }

    public function testValidateArrayElements()
    {
        $data = [
            'numbers' => [
                [
                    'one' => 1,
                    'two' => 2,
                    'three' => 3
                ]
            ]
        ];

        $this->commonValidationsObj->reset();
        $this->commonValidationsObj->setData($data);
        $this->commonValidationsObj->validateArrayElements('numbers', [
            'one' => [new PresenceOf(['allowEmpty' => true])]
        ]);
        $this->assertTrue($this->commonValidationsObj->validate());

        $this->commonValidationsObj->reset();
        $this->commonValidationsObj->validateArrayElements(
            'numbers',
            ['one' => new InlineValidator([
                'function' => function ($validation) {
                    return $validation->getValue('one') === 0;
                },
                'message' => 'one must have the integer 1'
            ])]
        );

        $this->assertFalse($this->commonValidationsObj->validate());
        $this->assertEquals(
            'one must have the integer 1',
            $this->commonValidationsObj->getMessages()
        );
    }


    public function testIsNotEmpty()
    {
        $filledObject = new \stdClass();
        $filledObject->number = 1;

        $data = [
            'empty_array' => [],
            'filled_array' => ['number' => 1],
            'empty_object' => new \stdClass(),
            'filled_object' => $filledObject
        ];

        $this->commonValidationsObj->setData($data);

        $this->commonValidationsObj->isNotEmpty('empty_array');
        $this->assertFalse($this->commonValidationsObj->validate());

        $this->commonValidationsObj->reset();

        $this->commonValidationsObj->isNotEmpty('empty_object');
        $this->assertFalse($this->commonValidationsObj->validate());

        $this->commonValidationsObj->reset();
        $this->commonValidationsObj->isNotEmpty('filled_array');
        $this->commonValidationsObj->isNotEmpty('filled_object');
        $this->assertTrue($this->commonValidationsObj->validate());
    }

    public function testValidateBooleanValue()
    {
        $data = [
            'boolean_true' => true,
            'boolean_false' => false,
            'string_true' => 'true',
            'string_false' => 'false',
            'integer_one' => 1,
            'integer_zero' => 0,
            'string_zero' => '0',
            'string_one' => '1',
            'random_string' => 'string',
            'random_integer' => '123123',
        ];

        $this->commonValidationsObj->setData($data);
        $this->commonValidationsObj->validateBooleanValue('boolean_true');
        $this->commonValidationsObj->validateBooleanValue('boolean_false');
        $this->commonValidationsObj->validateBooleanValue('integer_one');
        $this->commonValidationsObj->validateBooleanValue('integer_zero');
        $this->commonValidationsObj->validateBooleanValue('string_zero');
        $this->commonValidationsObj->validateBooleanValue('string_one');
        $this->assertTrue($this->commonValidationsObj->validate());

        $this->commonValidationsObj->reset();
        $this->commonValidationsObj->validateBooleanValue('random_string');
        $this->assertFalse($this->commonValidationsObj->validate());

        $this->commonValidationsObj->reset();
        $this->commonValidationsObj->validateBooleanValue('random_integer');
        $this->assertFalse($this->commonValidationsObj->validate());

        $this->commonValidationsObj->reset();
        $this->commonValidationsObj->validateBooleanValue('string_true');
        $this->assertFalse($this->commonValidationsObj->validate());

        $this->commonValidationsObj->reset();
        $this->commonValidationsObj->validateBooleanValue('string_false');
        $this->assertFalse($this->commonValidationsObj->validate());
    }
}
