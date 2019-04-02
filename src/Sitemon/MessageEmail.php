<?php

declare(strict_types=1);

namespace Sitemon;

use Sitemon\Interfaces\MessageInterface;

class MessageEmail implements MessageInterface
{
    private $receiver;


    private $subject;


    private $message;


    /**
     * sets properties for an email
     * @param string $receiver [description]
     * @param string $subject  [description]
     * @param string $message  [description]
     */
    public function __construct(string $receiver = '', string $subject = '', string $message = '')
    {
        $this->receiver = $receiver;
        $this->subject = $subject;
        $this->message = $message;
    }


    public function sendMessage(): bool
    {
        echo 'sends email to:' . $this->receiver . PHP_EOL;
        $sent = true;//mail($this->receiver, $this->subject, $this->message);

        return $sent;
    }


    public function setReceiver(string $receiver): void
    {
        $this->receiver = $receiver;
    }


    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }


    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}