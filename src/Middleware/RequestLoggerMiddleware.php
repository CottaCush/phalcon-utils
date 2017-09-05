<?php

namespace PhalconUtils\Middleware;

use Phalcon\Mvc\Micro;
use PhalconUtils\Constants\Services;
use PhalconUtils\Util\Logger;

/**
 * Class LoggerMiddleware
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Middleware
 */
class RequestLoggerMiddleware extends BaseMiddleware
{
    public function beforeExecuteRoute()
    {
        if ($this->isExcludedPath($this->getDI()->get(Services::CONFIG)->requestLogger->excluded_paths)) {
            return true;
        }

        /** @var \Phalcon\Http\Request $request */
        $request = $this->getDI()->get(Services::REQUEST);

        $config = $this->getDI()->get(Services::CONFIG);

        $logger = new Logger($config->application->logsDir . "requests.log");

        $logger->log('Request URL:' . $request->getURI(), \Phalcon\Logger::INFO);
        if ($request->isPost() || $request->isPut()) {
            $rawBody = $request->getRawBody();
            $logger->log('Request Body: ' . $rawBody, \Phalcon\Logger::INFO);
        }
    }

    public function call(Micro $application)
    {
        return true;
    }
}
