<?php

namespace App\Infrastructure;

use App\DTO\LocationDTO;
use App\DTO\RamMemoryCapacityDTO;
use App\DTO\StorageCapacityDTO;
use App\Domain\ServerRepository;
use App\Enums\HardDiskTypeEnum;

class ServerRepositorySpreadsheet implements ServerRepository
{
    private const FILE_NAME = 'LeaseWeb_servers_filters_assignment.xlsx';
    private $iterator;

    public function __construct($assetsDir)
    {
        $filePath = $assetsDir . self::FILE_NAME;
        $this->iterator = new ServerSpreadsheetIterator($filePath);
    }

    public function search(
        ?StorageCapacityDTO $storage = null,
        RamMemoryCapacityDTO|array $ramMemoryList = null,
        ?HardDiskTypeEnum $hardDiskType = null,
        ?LocationDTO $location = null
    ): array {
        $result = [];

        if (
            !$storage &&
            !$ramMemoryList &&
            !$hardDiskType &&
            !$location
        ) {
            return $this->getAll();
        }

        foreach ($this->iterator as $server) {
            if ($storage && ! $server->hasStorageCapacity($storage)) {
                continue;
            }

            if ($ramMemoryList && ! $server->hasRamMemoryCapacity($ramMemoryList)) {
                continue;
            }

            if ($hardDiskType && ! $server->hasHardDiskType($hardDiskType)) {
                continue;
            }

            if ($location && $server->getLocation() !== $location->name) {
                continue;
            }

            $result[] = $server;
        }

        return $result;
    }

    public function getAll(): array
    {
        $servers = [];

        foreach ($this->iterator as $server) {
            $servers[] = $server;
        }

        return $servers;
    }
}
