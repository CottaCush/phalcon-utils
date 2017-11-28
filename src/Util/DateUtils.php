<?php

namespace PhalconUtils\Util;

use Moment\Moment;

/**
 * Class DateUtils
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Util
 */
class DateUtils
{
    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return bool|string
     */
    public static function getCurrentDateTime()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return bool|string
     */
    public static function getCurrentDate()
    {
        return date('Y-m-d');
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $dob string date of birth in Y-m-d format
     * @return int
     */
    public static function getAgeFromDateOfBirth($dob)
    {
        return intval(abs((new Moment($dob))->fromNow()->getYears()));
    }

    /**
     * @author Olawale Lawal <wale@cottacush.com>
     * @param $age
     * @return string
     */
    public static function getDateOfBirthFromAge($age)
    {
        return (new Moment())->subtractYears($age)->format('Y-m-d');
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $date
     * @param $format
     * @return false|string
     */
    public static function format($date, $format)
    {
        if (!self::isDate($date)) {
            return $date;
        }

        return date($format, strtotime($date));
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $date
     * @return bool
     */
    public static function isDate($date)
    {
        return !is_null($date) && strtotime($date) !== false;
    }
}
