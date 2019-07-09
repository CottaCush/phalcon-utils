<?php

namespace PhalconUtils\tests;

use Phalcon\Test\UnitTestCase;
use PhalconUtils\Validation\RequestValidation;
use PhalconUtils\Validation\Validators\InlineValidator;

/**
 * Class ValidatorCodeTest
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\tests
 */
class ValidatorCodeTest extends UnitTestCase
{
    /**
     * test invalid imei number
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function testInvalidIMEINumber()
    {
        $validation = new RequestValidation(['number' => 0]);
        $validator = new InlineValidator([
            'function' => function () use ($validation) {
                return $validation->getValue('number') == 1;
            },
            'code' => 'invalid_number',
            'message' => 'Invalid number supplied'
        ]);
        $validation->add('number', $validator);
        $this->assertFalse($validator->validate($validation, 'number'));
        $this->assertEquals('invalid_number', $validation->getLastErrorCode());
        $this->assertEquals('Invalid number supplied', $validation->getMessages());
    }
}
