<?php

namespace Tests\Stubs;

use PhalconUtils\Validation\CommonValidations;
use PhalconUtils\Validation\RequestValidation;

/**
 * Class CommonValidationsImpl
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package Tests
 */
class CommonValidationsImpl extends RequestValidation
{
    use CommonValidations;
}
