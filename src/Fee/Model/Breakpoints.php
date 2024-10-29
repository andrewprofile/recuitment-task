<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Fee\Model;

use Money\Money;
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

    public function lowerBoundAmount(Money $amount): Money
    {
        $amounts = [];

        array_walk($this->breakpoints, static function (Breakpoint $breakpoint) use ($amount, &$amounts) {
            if ($breakpoint->amount()->lessThan($amount)) {
                $amounts[] = $breakpoint->amount();
            }
        });

        return !empty($amounts) ? max($amounts) : throw InvalidArgumentException::amountOutOfRange();
    }

    public function upperBoundAmount(Money $amount): Money
    {
        $amounts = [];

        array_walk($this->breakpoints, static function (Breakpoint $breakpoint) use ($amount, &$amounts) {
            if ($breakpoint->amount()->greaterThan($amount)) {
                $amounts[] = $breakpoint->amount();
            }
        });

        return !empty($amounts) ? min($amounts) : throw InvalidArgumentException::amountOutOfRange();
    }

    public function lowerFee(Money $amount): Money
    {
        return $this->fee($amount) ?? throw InvalidArgumentException::feeIsInvalid();
    }

    public function upperFee(Money $amount): Money
    {
        return $this->fee($amount) ?? throw InvalidArgumentException::feeIsInvalid();
    }

    public function fee(Money $amount): ?Money
    {
        $fee = null;
        array_walk($this->breakpoints, static function (Breakpoint $breakpoint) use ($amount, &$fee) {
            if ($breakpoint->amount()->equals($amount)) {
                $fee = $breakpoint->fee();
            }
        });

        return $fee;
    }
}
