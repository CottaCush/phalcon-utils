<?php

namespace PhalconUtils\Util;

use InvalidArgumentException;
use Phalcon\Di;
use PhalconUtils\Constants\Services;
use PhalconUtils\Templating\HandlebarsTemplatingEngine;
use PhalconUtils\Templating\TemplatingEngineInterface;

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
        $appHasTemplatingEngine = Di::getDefault()->has(Services::TEMPLATING_ENGINE);
        if (!$appHasTemplatingEngine) {
            $engine = new HandlebarsTemplatingEngine();
        } else {
            /** @var TemplatingEngineInterface $engine */
            $engine = Di::getDefault()->has(Services::TEMPLATING_ENGINE);
            if (!($engine instanceof TemplatingEngineInterface)) {
                throw new InvalidArgumentException('Templating Engine must be an instance of ' .
                    TemplatingEngineInterface::class);
            }
        }

        return $engine->renderTemplate($message, $params);
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

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $amount
     * @return string
     */
    public static function formatToNaira($amount)
    {
        if (is_null($amount) || !is_numeric($amount)) {
            return $amount;
        }

        return number_format($amount, 2) . 'NGN';
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $text
     * @param $suffix
     * @param $pluralForm
     * @return string
     */
    public static function appendCountableSuffix($text, $suffix, $pluralForm)
    {
        if (is_null($text) || !is_numeric($text)) {
            return $text;
        }

        $textWithLabel = ($text > 1) ? $text . ' ' . $pluralForm : $text . ' ' . $suffix;
        return $textWithLabel;
    }
}
