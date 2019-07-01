<?php

namespace PhalconUtils\tests\Http;

use Phalcon\Config;
use Phalcon\DiInterface;
use PhalconUtils\Constants\ResponseCodes;
use PhalconUtils\Http\Response;
use PhalconUtils\Util\ArrayUtils;

/**
 * Class HttpResponseTest
 * @author Kehinde Ladipo <kehinde.ladipo@cottacush.com>
 * @package PhalconUtils\tests
 */
class HttpResponseTest extends \UnitTestCase
{
    const UNEXPECTED_ERROR_OCCURRED = "unexpected_error_occurred";
    private $testData = ['id' => 1, 'title' => 'new post', 'body' => 'test post body'];


    /** @var Response */
    protected $response;

    public function setUp(DiInterface $di = null, Config $config = null)
    {
        parent::setUp();

        $this->response = new Response();
        $this->setOutputCallback(
            function () {
            }
        );
    }

    public function testSendError()
    {
        $this->response->sendError(ResponseCodes::UNEXPECTED_ERROR);
        $response = $this->getActualOutput();
        $this->assertJson($response);
        $this->assertContains(Response::RESPONSE_TYPE_ERROR, $response);
        $this->assertContains(ResponseCodes::UNEXPECTED_ERROR, $response);
    }

    public function testSendSuccess()
    {
        $this->response->sendSuccess($this->testData);
        $response = $this->getActualOutput();
        $this->assertJson($response);
        $this->assertContains(Response::RESPONSE_TYPE_SUCCESS, $response);

        $response = \GuzzleHttp\json_decode($response, true);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals(1, ArrayUtils::getValue($response, 'data.id'));
    }

    public function testSendFail()
    {
        $this->response->sendFail($this->testData);
        $response = $this->getActualOutput();
        $this->assertJson($response);
        $this->assertContains(Response::RESPONSE_TYPE_FAIL, $response);
        $response = \GuzzleHttp\json_decode($response, true);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('data', $response);
    }
}
