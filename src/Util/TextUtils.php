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
     * @return string
     */
    public static function formatPhoneNumberToInternationalFormat($countryCode, $number, $numberLength)
    {
        $actualNumber = substr(
            $number,
            -($numberLength),
            $numberLength
        );

        if (!$actualNumber) {
            return $number;
        }

        return '+' . $countryCode . $actualNumber;

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
