<?php

namespace App\Interfaces;

/**
 * Interface JSend
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Interfaces
 */
interface JSend
{
    const RESPONSE_TYPE_ERROR = 'error';
    const RESPONSE_TYPE_SUCCESS = 'success';
    const RESPONSE_TYPE_FAIL = 'fail';

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $code
     * @param int|string $http_status_code
     * @return mixed
     */
    public function sendError($code, $http_status_code = 200);

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $data
     * @return mixed
     */
    public function sendSuccess($data);

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $data
     * @param int|string $http_status_code
     * @return mixed
     */
    public function sendFail($data, $http_status_code = 500);
}
