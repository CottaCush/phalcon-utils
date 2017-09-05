<?php

namespace PhalconUtils\Http;

use Phalcon\Http\Client\Provider\Curl;
use Phalcon\Http\Client\Request;
use Phalcon\Http\Client\Response as ClientResponse;
use PhalconUtils\Util\ArrayUtils;

/**
 * Class HttpClient
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Library
 */
class HttpClient
{
    protected $provider;

    public function __construct($timeout = null)
    {
        $this->provider = Request::getProvider();
        if ($this->provider instanceof Curl) {
            if (!is_null($timeout)) {
                $this->provider->setTimeout(intval($timeout));
            }
            $this->provider->setOption(CURLOPT_SSL_VERIFYPEER, false);
            $this->provider->setOption(CURLOPT_SSL_VERIFYHOST, false);
        }
    }

    public function setHeader($key, $value)
    {
        $this->provider->header->set($key, $value);
    }

    public function get($url, $params = [])
    {
        $url = $url . '?' . http_build_query($params);
        return $this->provider->get($url);
    }


    public function post($url, $data)
    {
        return $this->provider->post($url, $data);
    }

    public function put($url, $data)
    {
        return $this->provider->put($url, $data);
    }

    public function delete($url)
    {
        return $this->provider->delete($url);
    }

    public function head($url, $params = [])
    {
        $url = $url . '?' . http_build_query($params);
        return $this->provider->head($url);
    }

    /**
     * @return \Phalcon\Http\Client\Provider\Curl|\Phalcon\Http\Client\Provider\Stream
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $response ClientResponse
     * @param int $successCode
     * @return bool
     */
    public static function isSuccessful($response, $successCode = 200)
    {
        return $response->header->statusCode === $successCode;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $url
     * @param array $params
     * @param null $key
     * @return array|string
     */
    public function getHeaders($url, $params = [], $key = null)
    {
        $url = $url . '?' . http_build_query($params);
        stream_context_set_default(['http' => ['method' => 'HEAD']]);
        $headers = get_headers($url, 1);
        return ($key) ? ArrayUtils::getValue($headers, $key) : $headers;
    }
}
