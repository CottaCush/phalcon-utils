<?php

namespace PhalconUtils\Util;

use Handlebars\Handlebars;

/**
 * Class TextUtils
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Util
 */
class TextUtils
{
    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $message
     * @param array $params
     * @return string
     */
    public static function getActualMessage($message, array $params)
    {
        $engine = new Handlebars();
        return $engine->render($message, $params);
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $countryCode
     * @param $number
     * @param $numberLength
     * @param null $prefix
     * @return string
     */
    public static function formatPhoneNumberToInternationalFormat($countryCode, $number, $numberLength, $prefix = null)
    {
        $plusPresent = strpos($number, '+') === 0;
        $plusPosition = ($plusPresent) ? 1 : 0;

        $countryCodePosition = strpos($number, $countryCode, $plusPosition);
        $countryCodePresent = $countryCodePosition !== false;

        $prefixPosition = $plusPosition;
        $prefixPosition += ($countryCodePresent) ? strlen($countryCode) : 0;

        $prefixPresent = strpos($number, $prefix, $prefixPosition) !== false;

        $prefixesLength = ($plusPresent) ? 1 : 0;
        $prefixesLength += ($countryCodePresent) ? strlen($countryCode) : 0;

        $prefixesLength += ($prefixPresent) ? strlen($prefix) : 0;


        $actualNumber = substr(
            $number,
            $prefixesLength,
            $numberLength
        );

        return  '+' . $countryCode . $actualNumber;

    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $string
     * @return string
     */
    public static function convertStringToHexadecimal($string)
    {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $hex .= dechex(ord($string[$i]));
        }
        return $hex;
    }
}
