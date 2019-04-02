<?php

declare(strict_types=1);

namespace Sitemon;

use Sitemon\Interfaces\MessageInterface;

class MessageText implements MessageInterface
{
    private $mobileNumber;


    private $message;


    /**
     * sets properties for a text message
     * @param string $mobileNumber [description]
     * @param string $message      [description]
     */
    public function __construct(string $mobileNumber = '', string $message = '')
    {
        $this->mobileNumber = $mobileNumber;
        $this->message = $message;
    }


    /**
     *  sends a text message(SMS) to a receiver mobile phone number
     * @param  string $mobileNumber [description]
     * @param  string $message      [description]
     * @return [type]               [description]
     */
    public function sendMessage(): bool
    {
        echo 'sends text to:' . $this->mobileNumber . PHP_EOL;
        // dummy method
        // sends $message to $mobileNUmber
        return true;
    }


    public function setMobileNumber(string $mobileNumber): void
    {
        $this->mobileNumber = $mobileNumber;
    }


    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}