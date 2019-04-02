<?php

namespace Sitemon\Actions;

use \Exception;

abstract class AbstractAction
{
    /**
     * loads view with provided data variable
     * @param  string $view
     * @param  string $data
     * @return void
     */
    public static function loadView(string $view, array $data = []): void
    {
        if (!file_exists('../src/Sitemon/Views/' . $view . '.php')) {
            throw new Exception(sprintf('View file %s not exists', $view));
        }
        include '../src/Sitemon/Views/' . $view . '.php';
    }
}