<?php

declare(strict_types=1);

namespace Sitemon;

use Sitemon\Interfaces\StoreDataInterface;

class FileWriter implements StoreDataInterface
{
    private $fileName;


    /**
     * sets config properties
     * @param array $config filename key defines name of a file to write to
     */
    public function __construct(array $config)
    {
        if (!isset($config['filename'])) {
            throw new Exception('File name is required');
        }
        if (empty($config['filename'])) {
            throw new Exception('File name can not be empty');
        }
        $this->fileName = $config['filename'];
    }


    /**
     * writes a file
     * @param  string $data string to write to a file
     * @return bool         false on failure, true on success
     */
    public function storeData(string $data): bool
    {
        $return = file_put_contents($this->fileName, $data, LOCK_EX);
        return $return !== false;
    }
}
