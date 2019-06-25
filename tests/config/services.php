<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Logger\Adapter\File;
use Phalcon\Mvc\Model\Metadata\Memory as MetaData;
use PhalconUtils\Constants\Services;
use PhalconUtils\Util\Logger;

$di = new FactoryDefault();

/**
 * Add Db Service
 */
$di->set('db', function () use ($config) {
    return new DbAdapter([
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname
    ]);
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function () {
    return new MetaData();
});

/**
 * Add models manager
 */
$di->setShared('modelsManager', function () {
    return new Phalcon\Mvc\Model\Manager();
});

/**
 * Add security
 */
$di->setShared('security', function () {
    $security = new \Phalcon\Security();
    $security->setWorkFactor(12);
    return $security;
});

$di->set(Services::LOGGER, function () use ($config) {
    //$fileTarget = new File($config->application->logsDir . 'general.log');
    $fileTarget = new File('general.log');

    $targets = [$fileTarget];

    $logger = new Logger($targets, ['debug' => $config->debug]);
    return $logger;
});

/**
 * Add config
 */
$di->set('config', $config);
