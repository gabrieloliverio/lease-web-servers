<?php

namespace App\Domain;

interface FilterRepository
{
    public function getStorage(): array;
    public function getRam(): array;
    public function getHardDiskType(): array;
    public function getLocation(): array;
}
