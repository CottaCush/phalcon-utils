<?php

namespace PhalconUtils\S3;

use Aws\S3\S3Client as AWSS3Client;

/**
 * Class S3Client
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class S3Client
{
    private $awsKey;
    private $awsSecret;
    private $bucket;
    private $namespace;
    private $client;
    private $error_messages = [];


    /**
     * Create an instance of S3Client
     * @param $awsKey
     * @param $awsSecret
     * @param $region
     * @param string $version
     * @param null $bucket
     * @param null $namespace
     */
    public function __construct($awsKey, $awsSecret, $region, $bucket = null, $namespace = null, $version = '2006-03-01')
    {
        $this->awsKey = $awsKey;
        $this->awsSecret = $awsSecret;
        $this->bucket = $bucket;
        $this->namespace = $namespace;
        $this->client = AWSS3Client::factory([
            'credentials' => [
                'key' => $awsKey,
                'secret' => $awsSecret
            ],
            'region' => $region,
            'version' => $version
        ]);
        $this->client->registerStreamWrapper();

        if (!is_null($bucket) && !$this->doesBucketExist($bucket)) {
            $this->createBucket($bucket);
        }
    }

    /**
     * Create a S3 Bucket
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $bucket_name
     * @return bool
     */
    public function createBucket($bucket_name)
    {
        if ($this->doesBucketExist($bucket_name)) {
            $this->addMessage("Bucket $bucket_name already exists");
            return true;
        }

        if (mkdir('s3://' . $bucket_name)) {
            return true;
        } else {
            $this->addMessage('Could not create bucket ' . $bucket_name);
            return false;
        }
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $bucket_name
     * @return bool
     */
    public function doesBucketExist($bucket_name)
    {
        return is_dir('s3://' . $bucket_name);
    }

    /**
     * Create an object
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $local_file_path
     * @param $file_name
     * @param null $bucket
     * @param null $namespace
     * @return bool
     */
    public function createObject($local_file_path, $file_name, $bucket = null, $namespace = null)
    {
        if (is_null($bucket) && is_null($this->bucket)) {
            $this->addMessage('Invalid bucket');
            return false;
        } else if (!is_null($this->bucket)) {
            $bucket = $this->bucket;
        }

        if (is_null($namespace) && is_null($this->namespace)) {
            $namespace = '';
        } else if (!is_null($this->namespace)) {
            $namespace = $this->namespace;
        }

        $namespace = (strlen($namespace) == 0) ? '' : $namespace . '/';

        if (!$this->doesBucketExist($bucket)) {
            $this->addMessage('Bucket does not exist');
            return false;
        }

        $s3_file_path = "s3://$bucket/$namespace$file_name";

        if (copy($local_file_path, $s3_file_path)) {
            return $file_name;
        } else {
            $this->addMessage("Could not upload file from $local_file_path to $s3_file_path");
            return false;
        }
    }

    /**
     * Get error messages
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return array
     */
    public function getMessages()
    {
        return $this->error_messages;
    }

    /**
     * Add a message to library error messages
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $message
     */
    public function addMessage($message)
    {
        $this->error_messages[] = $message;
    }

}