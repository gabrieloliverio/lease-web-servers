<?php

namespace App\Service;

use App\Infrastructure\FilterRepositorySpreadsheet;

class FilterTypeService {
    public function __construct(
        private $repository = new FilterRepositorySpreadsheet
    )
    { }

    public function getStorage() : array
    {
        return $this->repository->getStorage();
    }

    public function getRam() : array
    {
        return $this->repository->getRam();
    }

    public function getHardDiskType() : array
    {
        return $this->repository->getHardDiskType();
    }

    public function getLocation() : array
    {
        return $this->repository->getLocation();
    }
}