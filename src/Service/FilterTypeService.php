<?php

namespace App\Service;

use App\Domain\FilterRepository;

class FilterTypeService
{
    private $repository;

    public function __construct(FilterRepository $filterRepository)
    {
        $this->repository = $filterRepository;
    }

    public function getStorage(): array
    {
        return $this->repository->getStorage();
    }

    public function getRam(): array
    {
        return $this->repository->getRam();
    }

    public function getHardDiskType(): array
    {
        return $this->repository->getHardDiskType();
    }

    public function getLocation(): array
    {
        return $this->repository->getLocation();
    }
}
