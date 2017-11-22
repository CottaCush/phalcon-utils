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
    const NIGERIA_COUNTRY_CODE = '234';
    const NIGERIA_PHONE_NUMBER_LENGTH = 10;

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
        return self::formatPhoneNumberToInternationalFormat(
            self::NIGERIA_COUNTRY_CODE,
            $number,
            self::NIGERIA_PHONE_NUMBER_LENGTH
        );
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

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $key
     * @return bool|int|string
     * @credits https://stackoverflow.com/a/6846537/1215010
     */
    public static function getIniVariableAsBytes($key)
    {
        $val = trim($key);

        $last = strtolower($val[strlen($val) - 1]);
        $val = substr($val, 0, -1); // necessary since PHP 7.1; otherwise optional

        switch ($last) {
            case 'g':
                $val *= 1024;
            //keep multiplying
            case 'm':
                $val *= 1024;
            //keep multiplying
            case 'k':
                $val *= 1024;
                break;
        }

        return $val;
    }
}
