<?php

namespace PhalconUtils\tests\Utils;

use Phalcon\Config;
use Phalcon\DiInterface;
use PhalconUtils\Util\FileUtils;

/**
 * Class FileUtilsTest
 * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
 * @package PhalconUtils\tests\Utils
 */
class FileUtilsTest extends \UnitTestCase
{
    const FILE_NAME = 'test.csv';

    private $data;

    public function setUp(DiInterface $di = null, Config $config = null)
    {
        parent::setUp($di, $config);

        $handle = fopen(self::FILE_NAME, 'w+');
        $this->data = [
            ['id', 'name', 'email'],
            ['1', 'Nina', 'nina@xyz.com'],
            ['2', 'Joe', 'joe@abc.com']
        ];
        foreach ($this->data as $element) {
            fwrite($handle, implode(",", $element)."\n");
        }
    }

    /**
     * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
     */
    public function testReadCSV()
    {
        $data = FileUtils::readCSV(self::FILE_NAME);
        $data = array_filter($data);
        $this->assertEquals(count($this->data), count($data));
        $this->assertEquals($this->data, $data);
    }

    public function tearDown()
    {
        parent::tearDown();
        unlink(self::FILE_NAME);
    }
}
