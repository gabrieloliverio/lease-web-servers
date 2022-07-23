<?php

namespace App\Domain;

use App\Domain\Filter\LocationDTO;
use App\Domain\Filter\RamMemoryDTO;
use App\Domain\Filter\StorageDTO;
use App\Enums\HardDiskTypeEnum;

interface ServerRepository
{
    /**
     * Searches for a server, based on filters
     * 
     * @param StorageDTO $storage
     * @param RamMemoryDTO $ramMemory
     * @param HardDiskTypeEnum $hardDiskType
     * @param Location $location
     * @return array Array of Storage objects
     */
    public function search(
        StorageDTO $storage,
        RamMemoryDTO $ramMemory,
        HardDiskTypeEnum $hardDiskType,
        LocationDTO $location
    ) : array;
}
