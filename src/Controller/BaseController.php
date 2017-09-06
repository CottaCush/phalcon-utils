<?php
namespace PhalconUtils\Controller;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Resultset;
use PhalconUtils\Constants\HttpStatusCodes;
use PhalconUtils\Constants\ResponseCodes;
use PhalconUtils\Constants\StatusConstants;
use PhalconUtils\Http\Response;
use stdClass;

/**
 * Class BaseController
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Controller
 * @property Response $response
 */
class BaseController extends Controller
{
    /**
     * Get json raw body data
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param bool|false $assoc
     * @return array|bool|stdClass
     */
    public function getPostData($assoc = false)
    {
        $postData = $this->request->getJsonRawBody($assoc);

        if (!is_object($postData) && !$assoc) {
            return new stdClass();
        }

        if (!is_array($postData) && $assoc) {
            return [];
        }

        return $postData;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param null $message
     * @return mixed
     */
    public function sendBadRequestResponse($message = null)
    {
        return $this->response->sendError(
            ResponseCodes::INVALID_PARAMETERS,
            HttpStatusCodes::BAD_REQUEST_CODE,
            $message
        );
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param null $message
     * @param string $responseCode
     * @return mixed
     */
    public function sendServerErrorResponse($message = null, $responseCode = ResponseCodes::UNEXPECTED_ERROR)
    {
        return $this->response->sendError(
            $responseCode,
            HttpStatusCodes::INTERNAL_SERVER_ERROR_CODE,
            $message
        );
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param string $responseCode
     * @param null $message
     * @return mixed
     */
    public function sendNotFoundResponse($message = null, $responseCode = ResponseCodes::RECORD_NOT_FOUND)
    {
        return $this->response->sendError(
            $responseCode,
            HttpStatusCodes::NOT_FOUND_CODE,
            $message
        );
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $class
     * @param string $orderColumn
     * @param string $order
     * @param bool $return
     * @return mixed
     */
    public function getListOFValues($class, $orderColumn = 'name', $order = 'ASC', $return = false)
    {
        try {
            /** @var Resultset $listOfValues */
            $listOfValues = $class::find([
                'conditions' => 'is_active = ' . StatusConstants::STATUS_ACTIVE,
                'order' => $orderColumn . ' ' . $order
            ]);
        } catch (\Exception $exception) {
            $listOfValues = false;
        }

        if ($return) {
            return $listOfValues;
        }

        if (!$listOfValues) {
            return $this->sendServerErrorResponse('Could not fetch list of values');
        }

        return $this->response->sendSuccess($listOfValues->toArray());
    }
}
