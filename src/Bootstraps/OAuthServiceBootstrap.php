<?php

namespace PhalconUtils\Bootstrap;

use OAuth2\GrantType\ClientCredentials;
use OAuth2\GrantType\RefreshToken;
use OAuth2\Server;
use OAuth2\Storage\Pdo;
use Phalcon\Config;
use Phalcon\Di\Injectable;
use Phalcon\DiInterface;
use PhalconUtils\Constants\Services;

/**
 * Class OAuthServiceBootstrap
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Bootstrap
 */
class OAuthServiceBootstrap implements BootstrapInterface
{
    public function run(Injectable $app, DiInterface $di, Config $config)
    {
        $di->setShared(Services::OAUTH_SERVER, function () use ($di, $config) {
            $storage = new Pdo($di['db']->getInternalHandler());

            $server = new Server($storage, [
                'always_issue_new_refresh_token' => $config->oauth->always_issue_new_refresh_token,
                'refresh_token_lifetime' => $config->oauth->refresh_token_life_time,
                'access_lifetime' => $config->oauth->access_token_life_time,
                'id_lifetime' => $config->oauth->access_token_life_time
            ]);

            $server->addGrantType(new ClientCredentials($storage));
            $server->addGrantType(
                new RefreshToken(
                    $storage,
                    ['always_issue_new_refresh_token' => $config->oauth->always_issue_new_refresh_token]
                )
            );
            return $server;
        });
    }
}
