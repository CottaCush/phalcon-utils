AWS S3Client
============
Library to handle AWS S3 actions


Contributors
------------
Adeyemi Olaoye <yemi@cottacush.com>


Requirements
------------
* [AWS PHP SDK](https://github.com/aws/aws-sdk-php)
* [Amazon S3 PHP Stream Wrapper](https://blogs.aws.amazon.com/php/post/TxKV69TBGSONBU/Amazon-S3-PHP-Stream-Wrapper)



Usage
-----

* Setup Configuration

```php
return new \Phalcon\Config(array(
    ....
'aws' => array(
        'aws_key' => 'AWS_KEY',
        'aws_secret' => 'AWS_SECRET',
        's3' => array (
            'bucket' => 'test_bucket',
            'namespace' => 'test_namespace',
            'region' => 'test_region'
        )
    )
    ....    
);
```

* Setup S3Client in Dependency Injection

```php
 /**
  * Register s3 client as a lazy loaded service
  */
$di->set('S3Client', function () use ($config) {
    return new S3Client($config->aws->aws_key, $config->aws->aws_secret, $config->aws->s3->region, $config->aws->s3->bucket, $config->aws->s3->namespace);
});
```
