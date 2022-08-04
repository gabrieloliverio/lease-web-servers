<?php

namespace App\Domain;

use App\DTO\LocationDTO;
use App\DTO\RamMemoryCapacityDTO;
use App\DTO\StorageCapacityDTO;
use App\Enums\HardDiskTypeEnum;

interface ServerRepository
{
    /**
     * Searches for a server, based on filters
     *
     * @param StorageCapacityDTO $storage
     * @param RamMemoryCapacityDTO|array $ramMemory RamMemoryCapacityDTO or Array of RamMemoryCapacityDTO objects
     * @param HardDiskTypeEnum $hardDiskType
     * @param Location $location
     * @return array Array of Server objects
     */
    public function search(
        ?StorageCapacityDTO $storage = null,
        RamMemoryCapacityDTO|array $ramMemory = null,
        ?HardDiskTypeEnum $hardDiskType = null,
        ?LocationDTO $location = null
    ): array;
}
