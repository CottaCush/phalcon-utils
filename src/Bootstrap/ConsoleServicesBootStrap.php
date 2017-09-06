<?php

namespace PhalconUtils\Bootstrap;

use Phalcon\Config;
use Phalcon\Di\Injectable;
use Phalcon\DiInterface;
use PhalconUtils\Constants\Services;

/**
 * Class ConsoleServicesBootStrap
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Library\BootStrap
 */
class ConsoleServicesBootStrap extends DefaultServicesBootstrap
{

    public function run(Injectable $app, DiInterface $di, Config $config)
    {
        parent::run($app, $di, $config);

        $di->set(Services::CONSOLE, function () use ($app) {
            return $app;
        });
    }
}
