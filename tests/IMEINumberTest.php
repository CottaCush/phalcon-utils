<?php

namespace Tests;

use Phalcon\DI;
use Phalcon\Validation;
use PhalconUtils\Validation\RequestValidation;
use PhalconUtils\Validation\Validators\IMEINumber;

/**
 * Test Class for User Login
 * Class loginTest
 * @package Tests
 * @author Tega Oghenekohwo <tega@cottacush.com>
 */
class IMEINumberTest extends \UnitTestCase
{

    /**
     * test invalid imei number
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function testInvalidIMEINumber()
    {
        $validation = new RequestValidation();
        $validation->add('imei', new IMEINumber());

        //check for number with invalid string length
        $validation_status = $validation->validate(['imei' => '000000']);
        $this->assertFalse($validation_status);

        //check for number with valid string length
        $validation_status = $validation->validate(['imei' => '355555555555551']);
        $this->assertFalse($validation_status);
    }

    /**
     * test valid Imei number
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function testValidIMEInumber(){
        $validation = new RequestValidation();
        $validation->add('imei', new IMEINumber());

        $validation_status = $validation->validate(['imei' => '355555555555550']);
        $this->assertTrue($validation_status);

        $validation_status = $validation->validate(['imei' => '353918058092269']);
        $this->assertTrue($validation_status);
    }


}