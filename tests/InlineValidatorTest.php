<?php

namespace Tests;


use Phalcon\Exception;
use Phalcon\Test\UnitTestCase;
use PhalconUtils\Exceptions\ValidatorException;
use PhalconUtils\Validation\RequestValidation;
use PhalconUtils\Validation\Validators\InlineValidator;

/**
 * Class InlineValidatorTest
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package Tests
 */
class InlineValidatorTest extends UnitTestCase
{

    /**
     * test invalid function or closure
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function testInvalidClosure()
    {
        try {
            $validation = new RequestValidation();
            $validation->add('number', new InlineValidator());
            $validation->validate(['number' => 1]);
        } catch (Exception $ex) {
            $this->assertInstanceOf(ValidatorException::class, $ex);
        }

    }

    /**
     * test valid closure with invalid data
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function testValidClosureWithValidData()
    {
        $validation = new RequestValidation(['number' => 1]);
        $validation->add('number', new InlineValidator([
            'function' => function () use ($validation) {
                return $validation->getValue('number') == 1;
            }
        ]));
        $this->assertTrue($validation->validate());
    }

    /**
     * test valid closure with invalid data
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function testValidClosureWithInvalidData()
    {
        $validation = new RequestValidation(['number' => 2]);
        $validation->add('number', new InlineValidator([
            'function' => function () use ($validation) {
                return $validation->getValue('number') == 1;
            }
        ]));
        $this->assertFalse($validation->validate());
    }

}