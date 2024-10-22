<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Fee\Model;

use PragmaGoTech\Interview\Fee\Model\Exception\InvalidArgumentException;

final readonly class Term
{
    /**
     * @throws InvalidArgumentException
     */
    public function __construct(private int $loanTermMonths)
    {
        ($this->loanTermMonths < 12 || $this->loanTermMonths > 24) && throw InvalidArgumentException::termOutOfRange();
    }

    public function term(): int
    {
        return $this->loanTermMonths;
    }
}
