<?php

namespace App\Domain;

use InvalidArgumentException;

class PriceDescriptorParser
{
    private const DESCRIPTOR_REGEX = '/([^\d]+)(\d+\.\d+)/';

    public function parseValue(string $priceDescriptor) : float
    {
        $matches = [];
        preg_match(self::DESCRIPTOR_REGEX, $priceDescriptor, $matches);

        if (empty($matches) || !isset($matches[2])) {
            throw new InvalidArgumentException("Invalid descriptor format");
        }

        return $matches[2];
    }

    public function parseCurrency(string $priceDescriptor) : string
    {
        $matches = [];
        preg_match(self::DESCRIPTOR_REGEX, $priceDescriptor, $matches);

        if (empty($matches) || !isset($matches[1])) {
            throw new InvalidArgumentException("Invalid descriptor format");
        }

        return $matches[1];
    }
}
