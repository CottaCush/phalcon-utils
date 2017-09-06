<?php

namespace PhalconUtils\Constants;

use PhalconRest\Constants\Services as PhalconRestServices;

/**
 * Class BaseServices
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Constants
 */
class Services extends PhalconRestServices
{
    const CONFIG = 'config';
    const OAUTH_SERVER = 'oauthServer';
    const LOGGER = 'logger';
    const CONSOLE = 'console';
}
