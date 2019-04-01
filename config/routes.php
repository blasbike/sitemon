<?php

declare(strict_types=1);

$app->setRoutes(array(
                    'benchmark'     => [Sitemon\Actions\BenchmarkAction::class, 'index'],
                    'benchmark/run' => [Sitemon\Actions\BenchmarkAction::class, 'run'])
);
