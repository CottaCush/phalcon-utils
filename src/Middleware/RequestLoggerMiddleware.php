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
    /** @var  Logger */
    protected $logger;

    public function __construct($logTargets = [])
    {
        $config = $this->getDI()->get(Services::CONFIG);

        if (!$logTargets) {
            $fileTarget = new File($config->application->logsDir . 'requests.log');
            $logTargets = [$fileTarget];
        }

        $this->logger = new Logger($logTargets);
    }

    public function beforeExecuteRoute()
    {
        if ($this->isExcludedPath($this->getDI()->get(Services::CONFIG)->requestLogger->excluded_paths)) {
            return true;
        }

        /** @var \Phalcon\Http\Request $request */
        $request = $this->getDI()->get(Services::REQUEST);

        $this->logger->info('Request URL:' . $request->getURI());
        if ($request->isPost() || $request->isPut()) {
            $rawBody = $request->getRawBody();
            $this->logger->info('Request Body: ' . $rawBody);
        }
    }

    public function call(Micro $application)
    {
        return true;
    }
}
