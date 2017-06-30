<?php

namespace PhalconUtils\Util;

/**
 * Class ArrayUtils
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Util
 */
class ArrayUtils
{

    /**
     * Get unique array or column elements
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param array $array
     * @param $elementKey
     * @return array
     */
    public static function getUniqueColumnElements(array $array, $elementKey = null)
    {
        if (is_null($elementKey)) {
            return array_unique($array);
        }

        $uniqueElements = [];
        foreach ($array as $key => $value) {
            if (is_array($value) && isset($value[$elementKey])) {
                $value = $value[$elementKey];
            } elseif (is_object($value) && property_exists($value, $elementKey)) {
                $value = $value->{$elementKey};
            } else {
                continue;
            }
            if (!in_array($value, $uniqueElements, true)) {
                $uniqueElements[] = $value;
            }
        }
        return $uniqueElements;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param array $array
     * @param string $prefix
     * @param bool $mixed
     * @return array
     */
    public static function prependToArrayKeys(array $array, $prefix, $mixed = true)
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (!is_string($key)) {
                $result[] = $prefix . $value;
                continue;
            }

            $result[$prefix . $key] = $value;
        }

        return $result;
    }

    /**
     * Gets value from array or object
     * Copied from Yii2 framework
     * @link http://www.yiiframework.com/
     * @copyright Copyright (c) 2008 Yii Software LLC
     * @license http://www.yiiframework.com/license/
     * @param      $array
     * @param      $key
     * @param null $default
     * @return null
     * @author Qiang Xue <qiang.xue@gmail.com>
     * @author Adegoke Obasa <goke@cottacush.com>
     * @author Rotimi Akintewe <rotimi.akintewe@gmail.com>
     */
    public static function getValue($array, $key, $default = null)
    {
        if (!isset($array)) {
            return $default;
        }

        if ($key instanceof \Closure) {
            return $key($array, $default);
        }
        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $array = static::getValue($array, $keyPart);
            }
            $key = $lastKey;
        }
        if (is_array($array) && array_key_exists($key, $array)) {
            return $array[$key];
        }
        if (($pos = strrpos($key, '.')) !== false) {
            $array = static::getValue($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }

        if (is_object($array) && property_exists($array, $key)) {
            return $array->{$key};
        } elseif (is_array($array)) {
            return array_key_exists($key, $array) ? $array[$key] : $default;
        } else {
            return $default;
        }
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
