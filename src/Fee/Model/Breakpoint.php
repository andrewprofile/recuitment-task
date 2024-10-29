<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Fee\Model;

final readonly class Breakpoint
{
    public function __construct(private Amount $amount, private Fee $fee) {}

    public function amount(): float
    {
        return $this->amount->amount();
    }

    public function fee(): float
    {
        return $this->fee->fee();
    }
}
