<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Fee\Model;

use PragmaGoTech\Interview\Fee\Model\Exception\InvalidArgumentException;

final readonly class Amount
{
    /**
     * @throws InvalidArgumentException
     */
    public function __construct(private float $amount)
    {
        ($this->amount < 1000 || $amount > 20000) && throw InvalidArgumentException::amountOutOfRange();
    }

    public function amount(): float
    {
        return $this->amount;
    }
}
