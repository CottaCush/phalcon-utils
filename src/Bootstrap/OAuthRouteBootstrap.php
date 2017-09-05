<?php

namespace PhalconUtils\Bootstrap;

use Phalcon\Config;
use Phalcon\Di\Injectable;
use Phalcon\DiInterface;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\Collection as RouteHandler;

/**
 * Class OAuthRouteBootstrap
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\src\Bootstraps
 */
class OAuthRouteBootstrap implements BootstrapInterface
{
    public function run(Injectable $app, DiInterface $di, Config $config)
    {
        /** @var $api Micro */

        $router = new RouteHandler();
        $router->setHandler('PhalconUtils\Controller\OAuthController', true);
        $router->setPrefix('/oauth');
        $router->post('/token', 'token');
        $router->post('/authorize', 'authorize');
        $app->mount($router);
    }
}
