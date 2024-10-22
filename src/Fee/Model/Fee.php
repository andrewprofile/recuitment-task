<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Fee\Model;

use PragmaGoTech\Interview\Fee\Model\Exception\InvalidArgumentException;

final readonly class Fee
{
    public const float CONTINUE_PROPAGATION = -1.00;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(private float $fee)
    {
        (!is_float($this->fee) || $this->fee < 0.0) && throw InvalidArgumentException::feeIsInvalid();
    }

    public function fee(): float
    {
        return $this->fee;
    }
}
