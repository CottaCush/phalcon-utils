<?php

namespace PhalconUtils;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

/**
 * Class Mailer
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 */
class Mailer
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
     * @param null $from
     * @param array $cc
     * @param array $bcc
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return bool
     */
    public function send($text = "", $html = "", $subject = "", $to = [], $from = null, $cc = [], $bcc = [])
    {
        $this->failures = [];

        if (is_null($from)) {
            $from = $this->from;
        }

        $message = new Swift_Message($subject);
        $message->setFrom($from);
        $message->setBody($html, 'text/html');
        $message->addPart($text, 'text/plain');
        $message->setTo($to);
        $message->setCC($cc);
        $message->setBcc($bcc);


        $this->swiftClient->send($message, $this->failures);

        return (count($this->failures) == 0);
    }

}