<?php

namespace PhalconUtils\tests\Utils;

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

    /**
     * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
     */
    public function testConvertToNigerianPhoneFormat()
    {
        $number = TextUtils::convertToNigerianPhoneFormat(
            '08111111111'
        );
        $this->assertEquals('+2348111111111', $number);

        $number = TextUtils::convertToNigerianPhoneFormat(
            $number
        );
        $this->assertEquals('+2348111111111', $number);
    }

    /**
     * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
     */
    public function testConvertStringToHexadecimal()
    {
        $string = '123456';
        $this->assertEquals(bin2hex($string), TextUtils::convertStringToHexadecimal($string));
    }


    /**
     * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
     */
    public function testGetIniVariableAsBytes()
    {
        $value = '2M';
        $this->assertEquals(2 * 1024 * 1024, TextUtils::getIniVariableAsBytes($value));

        $value = '4G';
        $this->assertEquals(4 * 1024 * 1024 * 1024, TextUtils::getIniVariableAsBytes($value));

        $value = '6K';
        $this->assertEquals(6 * 1024, TextUtils::getIniVariableAsBytes($value));
    }
}
