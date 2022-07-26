<?php

namespace App\DataTransferObject;

class HardDiskTypeDTO
{
    public string $name;

    public function __construct(?string $name = '')
    {
        $this->name = $name;
    }
}
