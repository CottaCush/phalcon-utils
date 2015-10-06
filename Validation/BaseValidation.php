<?php


namespace PhalconUtils\Validation;

use Phalcon\Validation;

/**
 * Class BaseValidation
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Validation
 */
class BaseValidation extends Validation
{
    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed|string
     */
    public function getMessagesAsSentence()
    {
        $messages = [];

        foreach (parent::getMessages() as $message) {
            $messages[] = $message->getMessage();
        }
        return $this->getSentenceFromArray($messages);
    }

    /**
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
}