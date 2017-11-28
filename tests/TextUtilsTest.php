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
            10
        );

        $this->assertEquals('+2348111111111', $internationalNumber);

        $internationalNumber = TextUtils::formatPhoneNumberToInternationalFormat(
            '234',
            '8111111111',
            10
        );

        $this->assertEquals('+2348111111111', $internationalNumber);

        $internationalNumber = TextUtils::formatPhoneNumberToInternationalFormat(
            '234',
            '+2348000000000',
            10
        );

        $this->assertEquals('+2348000000000', $internationalNumber);

        $internationalNumber = TextUtils::formatPhoneNumberToInternationalFormat(
            '234',
            '23408111111111',
            10
        );

        $this->assertEquals('+2348111111111', $internationalNumber);

        $internationalNumber = TextUtils::formatPhoneNumberToInternationalFormat(
            '234',
            '+23408111111111',
            10
        );

        $this->assertEquals('+2348111111111', $internationalNumber);
    }


    public function testFormattingToNigerianNaira()
    {
        $template = '{{formatToNaira money}}';
        $data = ['money' => 500];
        $this->assertEquals('500.00NGN', TextUtils::getActualMessage($template, $data));
    }

    public function testAddCountableSuffix()
    {
        $template = '{{appendCountableSuffix number child children}}';
        $data = ['number' => 500];
        $this->assertEquals('500 children', TextUtils::getActualMessage($template, $data));
        $data = ['number' => 1];
        $this->assertEquals('1 child', TextUtils::getActualMessage($template, $data));
    }
}
