<?php

declare(strict_types=1);

namespace Sitemon;

use Sitemon\Interfaces\NotifierInterface;
use Sitemon\Interfaces\MessageInterface;
use Sitemon\Interfaces\ConditionInterface;

class Notifier implements NotifierInterface
{
    /**
     * array of notifications to send
     * @var array
     */
    private $notifications = [];


    /**
     * sends notifications if conditions are met
     * @return bool
     */
    public function notify(): bool
    {
        foreach($this->notifications as $notification) {
            if ($notification['condition']->condition()) {
                $notification['message']->sendMessage();
            }
        }
        return true;
    }


    /**
     * adds notification definition
     * @param MessageInterface   $msg       messanger to use
     * @param ConditionInterface $condition conditions to met
     */
    public function addNotification(MessageInterface $msg, ConditionInterface $condition): void
    {
        $this->notifications[] = ['message'=>$msg, 'condition'=>$condition];
    }
}
