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
     * @param RamMemoryDTO|array $ramMemory RamMemoryDTO or Array of RamMemoryDTO objects
     * @param HardDiskTypeEnum $hardDiskType
     * @param Location $location
     * @return array Array of Server objects
     */
    public function search(
        ?StorageDTO $storage = null,
        RamMemoryDTO|array $ramMemory = null,
        ?HardDiskTypeEnum $hardDiskType = null,
        ?LocationDTO $location = null
    ): array;
}
