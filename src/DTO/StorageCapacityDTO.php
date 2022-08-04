<?php

namespace App\DTO;

use App\Enums\DataUnitEnum;

class StorageCapacityDTO
{
    public function __construct(
        public int $capacity,
        public DataUnitEnum $dataUnit
    ) {
    }
}
