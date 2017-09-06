<?php

namespace PhalconUtils\Bootstrap;

use Phalcon\Di\Injectable;
use Phalcon\DiInterface;
use Phalcon\Config;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Security;
use PhalconUtils\Constants\Services;

/**
 * Class DefaultServicesBootStrap
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Bootstrap
 */
abstract class DefaultServicesBootstrap implements BootstrapInterface
{
    public function run(Injectable $app, DiInterface $di, Config $config)
    {
        $di->setShared(Services::MODELS_MANAGER, function () {
            return new ModelsManager();
        });

        $di->setShared(Services::SECURITY, function () {
            $security = new Security();
            $security->setWorkFactor(12);
            return $security;
        });

        $di->set(Services::CONFIG, $config);
    }
}
