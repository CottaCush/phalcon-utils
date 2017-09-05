<?php

namespace PhalconUtils\Controller;

use OAuth2\Request;
use OAuth2\Response;
use PhalconUtils\Constants\ResponseCodes;

/**
 * Class OAuthController
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Controller
 */
class OAuthController
{
    /**
     * Get access token
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    public function token()
    {
        $response = $this->oauthServer->handleTokenRequest(Request::createFromGlobals());
        if (!$response->isSuccessful()) {
            return $this->response->sendError(
                ResponseCodes::AUTH_ERROR,
                $response->getStatusCode(),
                $response->getParameter('error_description')
            );
        } else {
            return $this->response->sendSuccess($response->getParameters());
        }
    }

    /**
     * Get Authorization code
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    public function authorize()
    {
        $response = new Response();

        /** @var Response $response */
        $response = $this->oauthServer->handleAuthorizeRequest(Request::createFromGlobals(), $response, true);
        if (!$response->getParameters()) {
            $code = substr(
                $response->getHttpHeader('Location'),
                strpos($response->getHttpHeader('Location'), 'code=') + 5,
                40
            );
            return $this->response->sendSuccess(['code' => $code]);
        } else {
            return $this->response->sendError(
                ResponseCodes::AUTH_ERROR,
                $response->getStatusCode(),
                $response->getParameter('error_description')
            );
        }
    }
}
