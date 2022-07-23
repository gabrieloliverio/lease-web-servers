<?php

namespace App\Domain\Filter;

use App\Enums\DataUnitEnum;

class StorageDTO
{
    public function __construct(
        public int $capacity,
        public DataUnitEnum $dataUnit
    ) { }
}
