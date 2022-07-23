<?php

namespace App\Domain\Filter;

interface FilterRepository 
{
    public function getStorage() : array;
    public function getRam() : array;
    public function getHardDiskType() : array;
    public function getLocation() : array;
}