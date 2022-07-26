<?php

namespace App\DataTransferObject;

use Symfony\Component\Validator\Constraints as Assert;

class RamMemoryDTO
{
    #[Assert\Regex(
        pattern: '/(\d/+)(\w{2})',
        message: 'Invalid RAM format',
    )]
    public string $capacity;

    public function __construct(?string $capacity = '')
    {
        $this->capacity = $capacity;
    }
}
