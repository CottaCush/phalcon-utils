<?php

namespace PhalconUtils\Util;

use Phalcon\Http\Client\Response as HttpResponse;
use Phalcon\Logger as PhalconLogLevel;
use Phalcon\Logger\AdapterInterface;
use PhalconUtils\Http\BaseGateway;
use PhalconUtils\src\Exceptions\InvalidLoggerConfigException;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use SoapClient;

/**
 * Class Logger
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Util
 */
class Logger extends AbstractLogger implements LoggerInterface
{
    protected $debugEnabled;

    /** @var AdapterInterface[] | LoggerInterface[] */
    protected $logTargets;

    public function __construct(array $logTargets, $options = [])
    {
        $this->debugEnabled = ArrayUtils::getValue($options, 'debug', false);
        $this->logTargets = array_filter($logTargets);

        foreach ($this->logTargets as $logTarget) {
            if (!($logTarget instanceof LoggerInterface || $logTarget instanceof AdapterInterface)) {
                throw new InvalidLoggerConfigException(
                    'Log target must be instance of ' . LoggerInterface::class . ' or ' .
                    AdapterInterface::class . '; encountered ' . get_class($logTarget)
                );
            }
        }
    }

    public function debug($message, array $context = null)
    {
        if (!$this->debugEnabled) {
            return;
        }
        parent::debug($message, $context);
    }

    public function debugSoapServiceResponse($response)
    {
        $this->debug('Response Raw Data: ' . var_export($response, true));

        if (method_exists($response, 'getResponseData')) {
            $this->debug('Response Data: ' . $response->getResponseData());
        }

        if (method_exists($response, 'getResponseCode')) {
            $this->debug('Response Code: ' . $response->getResponseCode());
        }
    }

    public function debugSoapService(SoapClient $service)
    {
        $this->debug('Soap Request Headers: ' . $service->__getLastRequestHeaders());
        $this->debug('Soap Request: ' . $service->__getLastRequest());
        $this->debug('Soap Response Headers: ' . $service->__getLastResponseHeaders());
        $this->debug('Soap Response: ' . $service->__getLastResponse());
    }

    public function debugHttpServiceRequest(BaseGateway $service, array $requestData, $url)
    {
        $this->debug('Sending Request to ' . get_class($service) . ':' . json_encode($requestData) .
            ' URL: ' . $service->buildURL($url));
    }

    public function debugHttpServiceResponse(BaseGateway $service, HttpResponse $response)
    {
        $this->debug('Service Response ' . get_class($service) . ':' . var_export($response, true));
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        /** @var LoggerInterface|AdapterInterface $logTarget */
        foreach ($this->logTargets as $logTarget) {
            if (($logTarget instanceof AdapterInterface)) {
                $logTarget->log($this->getPhalconLoggerLevel($level), $message, $context);
            } else {
                $logTarget->log($level, $message, $context);
            }
        }
    }

    /**
     * Get Phalcon Log Level from PSR Log level
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $level
     * @return null
     */
    private function getPhalconLoggerLevel($level)
    {
        $levelMap = [
            LogLevel::INFO => PhalconLogLevel::INFO,
            LogLevel::DEBUG => PhalconLogLevel::DEBUG,
            LogLevel::ALERT => PhalconLogLevel::ALERT,
            LogLevel::CRITICAL => PhalconLogLevel::CRITICAL,
            LogLevel::EMERGENCY => PhalconLogLevel::EMERGENCY,
            LogLevel::ERROR => PhalconLogLevel::ERROR,
            LogLevel::NOTICE => PhalconLogLevel::NOTICE,
            LogLevel::WARNING => PhalconLogLevel::WARNING
        ];

        return ArrayUtils::getValue($levelMap, $level, PhalconLogLevel::INFO);
    }
}
