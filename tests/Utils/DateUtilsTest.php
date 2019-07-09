<?php

namespace PhalconUtils\tests\Utils;

use PhalconUtils\Util\DateUtils;

/**
 * Class DateUtilsTest
 * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
 * @package CottaCush\Yii2\Tests\Date
 */
class DateUtilsTest extends \UnitTestCase
{
    const FORMAT_MYSQL_STYLE_NO_TIME = 'Y-m-d';
    const FORMAT_MYSQL_STYLE = 'Y-m-d H:i:s';
    const FORMAT_SHORT = 'jS M Y';

    /**
     * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
     */
    public function testGetCurrentDateTime()
    {
        $dateTime = DateUtils::getCurrentDateTime();
        $this->assertEquals(date(self::FORMAT_MYSQL_STYLE), $dateTime);
    }

    /**
     * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
     */
    public function testGetCurrentDate()
    {
        $date = DateUtils::getCurrentDate();
        $this->assertEquals(date(self::FORMAT_MYSQL_STYLE_NO_TIME), $date);
    }

    /**
     * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
     */
    public function testGetDateOfBirthFromAge()
    {
        $age = 20;
        $dob = DateUtils::getDateOfBirthFromAge($age);
        $this->assertEquals(date('Y-m-d', strtotime("$age years ago")), $dob);

        $calculatedAge = DateUtils::getAgeFromDateOfBirth($dob);
        $this->assertEquals($calculatedAge, $age);
    }

    /**
     * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
     */
    public function testFormatDate()
    {
        $date = DateUtils::format(date(self::FORMAT_SHORT), self::FORMAT_MYSQL_STYLE_NO_TIME);
        $this->assertEquals(date(self::FORMAT_MYSQL_STYLE_NO_TIME), $date);
    }

    /**
     * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
     */
    public function testIsDate()
    {
        $date = date(self::FORMAT_MYSQL_STYLE_NO_TIME);
        $this->assertEquals(true, DateUtils::isDate($date));

        $invalidDate = 'not a valid date';
        $this->assertEquals(false, DateUtils::isDate($invalidDate));
    }
}
