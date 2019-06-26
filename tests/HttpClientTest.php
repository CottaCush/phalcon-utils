<?php

namespace PhalconUtils\tests;

use Phalcon\Config;
use Phalcon\DiInterface;
use Phalcon\Http\Client\Provider\Curl;
use PhalconUtils\Constants\HttpStatusCodes;
use PhalconUtils\Http\BaseGateway;
use PhalconUtils\Http\HttpClient;
use PhalconUtils\Util\ArrayUtils;

/**
 * Class HttpClientTest
 * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
 * @package PhalconUtils\tests
 */
class HttpClientTest extends \UnitTestCase
{
    const BASE_URL = "http://jsonplaceholder.typicode.com";
    private $testPostParams = ['title' => 'new post', 'body' => 'test post body', 'userId' => 1];

    /** @var  $httpClient HttpClient */
    protected $httpClient;
    /** @var BaseGateway */
    protected $gateway;

    private $testPostId;

    public function setUp(DiInterface $di = null, Config $config = null)
    {
        parent::setUp();

        $this->httpClient = new HttpClient();

        $this->gateway = new BaseGateway(self::BASE_URL, null);
    }

    public function testCurlAgent()
    {
        $this->assertNotNull($this->httpClient->getProvider());
        $this->assertInstanceOf(Curl::class, $this->httpClient->getProvider());
    }

    public function testGetRequest()
    {
        $response = $this->httpClient->get(self::BASE_URL);

        $this->assertTrue(HttpClient::isSuccessful($response));
    }

    public function testPostRequest()
    {
        $url = self::BASE_URL . "/posts";
        $response = $this->httpClient->post($url, json_encode($this->testPostParams));
        $this->assertTrue(HttpClient::isSuccessful($response, HttpStatusCodes::RESOURCE_CREATED_CODE));

        $response = $this->gateway->decodeJsonResponse($response, false, true, HttpStatusCodes::RESOURCE_CREATED_CODE);

        $postId = ArrayUtils::getValue($response, 'id');
        $this->assertNotNull($postId);
        $this->testPostId = $postId;
    }

    public function testPutRequest()
    {
        $url = self::BASE_URL . "/posts/$this->testPostId";
        $data = $this->testPostParams;
        $title = 'foo';
        $data['title'] = $title;
        $this->httpClient->setHeader('Content-Type', 'application/json');

        $response = $this->httpClient->post($url, json_encode($data));
        $this->assertTrue(HttpClient::isSuccessful($response, HttpStatusCodes::RESOURCE_CREATED_CODE));

        $response = $this->gateway->decodeJsonResponse($response, false, true, HttpStatusCodes::RESOURCE_CREATED_CODE);
        $this->assertEquals($title, ArrayUtils::getValue($response, 'title'));
    }

    public function testSetHeaders()
    {
        $contentType = 'application/json';

        $this->httpClient->setHeader('Content-Type', $contentType);

        $this->assertEquals($this->httpClient->getProvider()->header->get('Content-Type'), $contentType);
    }

    /**
     * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
     * @depends testPutRequest
     */
    public function testDeleteRequest()
    {
        $response = $this->httpClient->delete(self::BASE_URL . "/posts/1");
        $this->assertTrue(HttpClient::isSuccessful($response));
    }
}
