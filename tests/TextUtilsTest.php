<?php

namespace PhalconUtils\tests;

use PhalconUtils\Util\TextUtils;

/**
 * Class TextUtilsTest
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\tests
 */
class TextUtilsTest extends \UnitTestCase
{

    public function testFormatPhoneNumberToInternationalFormat()
    {
        $internationalNumber = TextUtils::formatPhoneNumberToInternationalFormat(
            '234',
            '08111111111',
            10,
            '0'
        );

        $this->assertEquals('+2348111111111', $internationalNumber);

        $internationalNumber = TextUtils::formatPhoneNumberToInternationalFormat(
            '234',
            '8111111111',
            10,
            '0'
        );

        $this->assertEquals('+2348111111111', $internationalNumber);

        $internationalNumber = TextUtils::formatPhoneNumberToInternationalFormat(
            '234',
            '+2348111111111',
            10,
            '0'
        );

        $this->assertEquals('+2348111111111', $internationalNumber);

        $internationalNumber = TextUtils::formatPhoneNumberToInternationalFormat(
            '234',
            '23408111111111',
            10,
            '0'
        );

        $this->assertEquals('+2348111111111', $internationalNumber);

        $internationalNumber = TextUtils::formatPhoneNumberToInternationalFormat(
            '234',
            '+23408111111111',
            10,
            '0'
        );

        $this->assertEquals('+2348111111111', $internationalNumber);
    }
}
