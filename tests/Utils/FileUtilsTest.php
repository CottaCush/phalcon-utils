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
    const IMAGE_NAME = 'test.jpg';

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

        $image = imagecreate(500, 500);
        imagejpeg($image, self::IMAGE_NAME);
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

    /**
     * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
     */
    public function testGetBase64ImageMimeType()
    {
        $fileData = base64_encode(file_get_contents(self::IMAGE_NAME));

        $this->assertEquals('image/jpeg', FileUtils::getBase64ImageMimeType($fileData));
    }

    public function tearDown()
    {
        parent::tearDown();
        unlink(self::FILE_NAME);
        unlink(self::IMAGE_NAME);
    }
}
