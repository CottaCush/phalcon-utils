<?php

namespace App\Middleware;

use Phalcon\Events\Event;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\User\Plugin;

/**
 * @author Adegoke Obasa <goke@cottacush.com>
 */
class CORSMiddleware extends Plugin
{

    public function beforeHandleRoute(Event $event, Micro $app)
    {
        $app->before(function () use ($app) {
            $origin = $app->request->getHeader("ORIGIN") ? $app->request->getHeader("ORIGIN") : '*';

            $app->response->setHeader("Access-Control-Allow-Origin", $origin)
                ->setHeader("Access-Control-Allow-Methods", 'GET,PUT,POST,DELETE,OPTIONS')
                ->setHeader("Access-Control-Allow-Headers", 'Origin, X-Requested-With, Content-Range, ' .
                    'Content-Disposition, Content-Type, Authorization')
                ->setHeader("Access-Control-Allow-Credentials", true);
        });

        $app->options('/{catch:(.*)}', function () use ($app) {
            $app->response->setStatusCode(200, "OK")->send();
        });
    }
}
