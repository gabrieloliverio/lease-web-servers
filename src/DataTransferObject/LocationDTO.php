<?php

namespace App\DataTransferObject;

class LocationDTO
{
    public string $name;

    public function __construct(?string $name = '')
    {
        $this->name = $name;
    }
}
