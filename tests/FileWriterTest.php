<?php

declare(strict_types=1);

namespace Sitemon;

use PHPUnit\Framework\TestCase;


class FileWriterTest extends TestCase
{
    /**
     *
     * @test
     */
    public function storeData()
    {
        $file = new FileWriter(['filename'=>'log.txt']);
        $file->storeData('testing file');
        $this->assertFileExists('log.txt');
    }
}