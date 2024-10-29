<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Fee\Model;

use PragmaGoTech\Interview\Fee\Model\Exception\InvalidArgumentException;

/**
 * @extends \ArrayObject<int, Breakpoint>
 */
final class Breakpoints extends \ArrayObject
{
    /**
     * @param Breakpoint[] $breakpoints
     */
    public function __construct(private array $breakpoints = [])
    {
        parent::__construct($breakpoints);
    }

    public function lowerBoundAmount(float $amount): float
    {
        $amounts = [];

        array_walk($this->breakpoints, static function (Breakpoint $breakpoint) use ($amount, &$amounts) {
            if ($breakpoint->amount() < $amount) {
                $amounts[] = $breakpoint->amount();
            }
        });

        return !empty($amounts) ? max($amounts) : throw InvalidArgumentException::amountOutOfRange();
    }

    public function upperBoundAmount(float $amount): float
    {
        $amounts = [];

        array_walk($this->breakpoints, static function (Breakpoint $breakpoint) use ($amount, &$amounts) {
            if ($breakpoint->amount() > $amount) {
                $amounts[] = $breakpoint->amount();
            }
        });

        return !empty($amounts) ? min($amounts) : throw InvalidArgumentException::amountOutOfRange();
    }

    public function lowerFee(float $amount): float
    {
        return $this->fee($amount) ?? throw InvalidArgumentException::feeIsInvalid();
    }

    public function upperFee(float $amount): float
    {
        return $this->fee($amount) ?? throw InvalidArgumentException::feeIsInvalid();
    }

    public function fee(float $amount): ?float
    {
        $fee = null;
        array_walk($this->breakpoints, static function (Breakpoint $breakpoint) use ($amount, &$fee) {
            if ($breakpoint->amount() === $amount) {
                $fee = $breakpoint->fee();
            }
        });

        return $fee;
    }
}
