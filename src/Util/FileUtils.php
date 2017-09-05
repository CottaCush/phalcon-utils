<?php

namespace PhalconUtils\Util;

/**
 * Class FileUtils
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\src\Util
 */
class FileUtils
{
    /**
     * Reads a CSV file
     * @credit http://www.codedevelopr.com/articles/reading-csv-files-into-php-array/
     * @param $csvFile
     * @return array
     */
    public static function readCSV($csvFile)
    {
        ini_set('auto_detect_line_endings', true);
        $file_handle = fopen($csvFile, 'r');
        while (!feof($file_handle)) {
            $line_of_text[] = fgetcsv($file_handle, 1024);
        }
        fclose($file_handle);
        return $line_of_text;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $imageData
     * @return int|null|string
     * @credits http://stackoverflow.com/a/35996452/1215010
     */
    public static function getBase64ImageMimeType($imageData)
    {
        $imageData = base64_decode($imageData);
        $f = finfo_open();
        $mimeType = finfo_buffer($f, $imageData, FILEINFO_MIME_TYPE);
        return ($mimeType ?: null);
    }
}
