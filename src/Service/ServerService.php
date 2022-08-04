<?php

namespace App\Service;

use App\DTO\LocationDTO;
use App\DTO\RamMemoryCapacityDTO;
use App\DTO\StorageCapacityDTO;
use App\Domain\ServerRepository;
use App\Enums\HardDiskTypeEnum;

class ServerService
{
    public function __construct(private ServerRepository $repository)
    {
    }

    public function search(
        ?StorageCapacityDTO $storage = null,
        RamMemoryCapacityDTO|array $ramMemory = null,
        ?HardDiskTypeEnum $hardDiskType = null,
        ?LocationDTO $location = null
    ) {
        return $this->repository->search($storage, $ramMemory, $hardDiskType, $location);
    }
}
