<?php

namespace PhalconUtils\Middleware;

use Phalcon\Logger\Adapter\File;
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

        $fileTarget = new File($config->application->logsDir . 'requests.log');
        $logger = new Logger([$fileTarget]);

        $logger->info('Request URL:' . $request->getURI());
        if ($request->isPost() || $request->isPut()) {
            $rawBody = $request->getRawBody();
            $logger->info('Request Body: ' . $rawBody);
        }
    }

    public function call(Micro $application)
    {
        return true;
    }
}
