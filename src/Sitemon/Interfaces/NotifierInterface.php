<?php

declare(strict_types=1);

namespace Sitemon\Interfaces;

interface NotifierInterface
{
    /**
     * sends out notifications
     * @return bool
     */
	public function notify(): bool;
}
