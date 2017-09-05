<?php

namespace PhalconUtils\Middleware;

use OAuth2\Request;
use OAuth2\Server;
use Phalcon\Mvc\Micro;
use PhalconUtils\Constants\ResponseCodes;
use PhalconUtils\Constants\Services;
use PhalconUtils\Http\Response;

/**
 * Class OAuthMiddleware
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Middleware
 */
class OAuthMiddleware extends BaseMiddleware
{
    public function call(Micro $application)
    {
        if ($this->isExcludedPath($this->getDI()->get(Services::CONFIG)->oauth->excluded_paths)) {
            return true;
        }

        /** @var Server $oauthServer */
        $oauthServer = $params = $this->getDI()->get(Services::OAUTH_SERVER);

        /** @var \PhalconUtils\Http\Response $response */
        $response = $this->getDI()->get(Services::RESPONSE);
        if (!$oauthServer->verifyResourceRequest(new Request($this->getDI()->get(Services::REQUEST)->getQuery()))) {
            $oauthServerResponse = $oauthServer->getResponse();
            if (!$oauthServerResponse->getParameters()) {
                $response->sendError(ResponseCodes::AUTH_ACCESS_TOKEN_REQUIRED, $oauthServerResponse->getStatusCode());
            } else {
                $response->sendError(
                    ResponseCodes::AUTH_ERROR,
                    $oauthServerResponse->getStatusCode(),
                    $oauthServerResponse->getParameter('error_description')
                );
            }
            return false;
        }

        return true;
    }
}
