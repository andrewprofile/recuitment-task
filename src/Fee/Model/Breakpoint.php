<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Fee\Model;

use Money\Money;

final readonly class Breakpoint
{
    public function __construct(private Money $amount, private Money $fee) {}

    public function amount(): Money
    {
        return $this->amount;
    }

    public function fee(): Money
    {
        return $this->fee;
    }
}
