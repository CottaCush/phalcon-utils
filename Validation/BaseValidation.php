<?php

namespace PhalconUtils\Validation;

use Phalcon\Validation;
use Phalcon\Validation\MessageInterface;
use Phalcon\Validation\Validator;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class BaseValidation
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Validation
 */
class BaseValidation extends Validation
{
    /**
     * @param string|null $namespace
     */
    public function __construct($namespace = null)
    {
        $this->namespace = $namespace;
        parent::__construct();
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed|string
     */
    public function getMessages()
    {
        $messages = [];

        foreach (parent::getMessages() as $message) {
            $messages[] = $message->getMessage();
        }
        return $this->getSentenceFromArray($messages);
    }

    /**
     * Get messages as a sentence
     * @credits https://github.com/yiisoft/yii2/blob/master/framework/helpers/BaseInflector.php
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param array $words
     * @param string $twoWordsConnector
     * @param null $lastWordConnector
     * @param string $connector
     * @return mixed|string
     */
    private function getSentenceFromArray(array $words, $twoWordsConnector = ' and ', $lastWordConnector = null, $connector = ', ')
    {
        if ($lastWordConnector === null) {
            $lastWordConnector = $twoWordsConnector;
        }
        switch (count($words)) {
            case 0:
                return '';
            case 1:
                return reset($words);
            case 2:
                return implode($twoWordsConnector, $words);
            default:
                return implode($connector, array_slice($words, 0, -1)) . $lastWordConnector . end($words);
        }
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param array $fields
     * @param array $options
     */
    public function setRequiredFields(array $fields, $options = [])
    {
        if (!isset($options['message'])) {
            $options['message'] = ":field is required";
        }
        if (!isset($options['allowEmpty'])) {
            $options['allowEmpty'] = false;
        }

        foreach ($fields as $field) {
            $this->add($field, new PresenceOf($options));
        }
    }

    /**
     * Validate a set of data according to a set of rules
     *
     * @param array|object $data
     * @param object $entity
     * @return bool
     */
    public function validate($data = null, $entity = null)
    {
        if (!is_null($this->namespace) && !is_null($data)) {
            if (is_array($data) && isset($data[$this->namespace])) {
                $data = $data[$this->namespace];
            } else if (is_object($data) && property_exists($data, $this->namespace)) {
                $data = $data->{$this->namespace};
            } else {
                $data = null;
            }
        }

        return !count(parent::validate($data, $entity));
    }


    /**
     * @author Adeyemi Olaoye <yemexx1@gmail.com>
     * @param MessageInterface $message
     * @return Validation
     */
    public function appendMessage(MessageInterface $message)
    {
        if (!is_null($this->namespace)) {
            $message->setMessage(str_replace($message->getField(), $this->namespace . '.' . $message->getField(), $message->getMessage()));
        }

        return parent::appendMessage($message);
    }

    /**
     * @return null
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param null $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }


}