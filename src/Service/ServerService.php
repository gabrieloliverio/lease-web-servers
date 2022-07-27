<?php

namespace App\Service;

use App\Domain\Filter\LocationDTO;
use App\Domain\Filter\RamMemoryDTO;
use App\Domain\Filter\StorageDTO;
use App\Domain\ServerRepository;
use App\Enums\HardDiskTypeEnum;

class ServerService
{
    public function __construct(private ServerRepository $repository)
    {
    }

    public function search(
        ?StorageDTO $storage = null,
        RamMemoryDTO|array $ramMemory = null,
        ?HardDiskTypeEnum $hardDiskType = null,
        ?LocationDTO $location = null
    ) {
        return $this->repository->search($storage, $ramMemory, $hardDiskType, $location);
    }
}
