<?php

namespace PhalconUtils\Validation;

use Phalcon\Validation\Message;

/**
 * Class CustomMessage
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Validation
 */
class CustomMessage extends Message
{
    protected $code;

    public function __construct($message, $field = null, $type = null, $code = null)
    {
        $this->setCode($code);
        parent::__construct($message, $field, $type);
    }

    /**
     * @return null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param null $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }
}
