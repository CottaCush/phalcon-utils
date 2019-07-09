<?php

namespace PhalconUtils\tests\Utils;

use PhalconUtils\Util\ArrayUtils;

/**
 * Class FileUtilsTest
 * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
 * @package PhalconUtils\tests\Utils
 */
class ArrayUtilsTest extends \UnitTestCase
{

    /**
     * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
     */
    public function testReadCSV()
    {
        $prefix = 'pre_';

        $keys = ['hello', 'world'];
        $values = [1, 2];
        $array = array_combine($keys, $values);
        $array = ArrayUtils::prependToArrayKeys($array, $prefix);

        $i = 0;
        foreach ($array as $key => $value) {
            $expected = $prefix . $keys[$i];
            $this->assertEquals($expected, $key);
            $i++;
        }
    }
}
