<?php

namespace App\Domain;

class Price
{
    public function __construct(
        private float $value,
        private string $currency
    ) {
    }

    /**
     * Makes a Price instance from descriptor
     *
     * @param string $priceDescriptor Descriptor like "$$199.99"
     * @return Price
     */
    public static function makeFromDescriptor(string $priceDescriptor): Price
    {
        $parser = new PriceDescriptorParser($priceDescriptor);
        $value = $parser->parseValue($priceDescriptor);
        $currency = $parser->parseCurrency($priceDescriptor);

        return new Price($value, $currency);
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function __toString()
    {
        return $this->currency . $this->value;
    }
}
