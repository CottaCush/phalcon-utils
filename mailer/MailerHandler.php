<?php

namespace PhalconUtils\Mailer;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

/**
 * Class Mailer
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class MailerHandler
{
    /** @var  Swift_Mailer $swiftClient */
    private $swiftClient;
    private $from;
    public $failures = [];

    /**
     * @param $username
     * @param $password
     * @param $smtp_host
     * @param $smtp_port
     * @param null $defaultFrom
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function __construct($username, $password, $smtp_host, $smtp_port, $defaultFrom = null)
    {
        $transport = Swift_SmtpTransport::newInstance($smtp_host, $smtp_port);
        $transport->setUsername($username);
        $transport->setPassword($password);
        $this->swiftClient = Swift_Mailer::newInstance($transport);
        if (!is_null($defaultFrom)) {
            $this->from = $defaultFrom;
        }
    }


    /**
     * @param string $text
     * @param string $html
     * @param string $subject
     * @param array $to
     * @param array $params
     * @param null $from
     * @param array $cc
     * @param array $bcc
     * @return bool
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public function send($text = "", $html = "", $subject = "", $to = [], $from = null, $cc = [], $bcc = [], $params = [])
    {
        $this->failures = [];

        if (is_null($from)) {
            $from = $this->from;
        }

        $message = new Swift_Message($subject);
        $message->setFrom($from);
        $message->setBody($this->getActualMessage($html, $params), 'text/html');
        $message->addPart($this->getActualMessage($text, $params), 'text/plain');
        $message->setTo($to);
        $message->setCC($cc);
        $message->setBcc($bcc);


        $this->swiftClient->send($message, $this->failures);

        return (count($this->failures) == 0);
    }

    /**
     * @param $message
     * @param $params
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    private static function getActualMessage($message, $params)
    {
        foreach ($params as $param => $value) {
            $message = str_replace('{{' . $param . '}}', $value, $message);
        }
        return $message;
    }

}