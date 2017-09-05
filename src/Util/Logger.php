<?php

namespace PhalconUtils\Util;

use Phalcon\Logger\Adapter\File;
use Phalcon\Http\Client\Response as HttpResponse;
use PhalconUtils\Http\BaseGateway;
use SoapClient;

/**
 * Class Logger
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Util
 */
class Logger extends File
{
    protected $debugEnabled;

    public function __construct($name, $options = null)
    {
        $this->debugEnabled = ArrayUtils::getValue($options, 'debug', false);
        parent::__construct($name, $options);
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
}
