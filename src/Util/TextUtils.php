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
     * @param $number
     * @return string
     */
    public static function convertToNigerianPhoneFormat($number)
    {
        return self::formatPhoneNumberToInternationalFormat('234', $number, 10);
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

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $mixed
     * @return array|string
     * @credits http://stackoverflow.com/questions/10199017/how-to-solve-json-error-utf8-error-in-php-json-decode
     */
    public static function utf8ize($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = self::utf8ize($value);
            }
        } elseif (is_object($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed->$key = self::utf8ize($value);
            }
        } elseif (is_string($mixed)) {
            return utf8_encode($mixed);
        }
        return $mixed;
    }
}
