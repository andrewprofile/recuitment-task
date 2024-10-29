<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Unit\Fee\Fake;

use Money\Money;
use PragmaGoTech\Interview\Fee\FeeCalculator;
use PragmaGoTech\Interview\Fee\Model\Exception\InvalidArgumentException;
use PragmaGoTech\Interview\Fee\Model\Loan;

final class LinearInterpolatedFeeCalculatorFake implements FeeCalculator
{
    public function calculate(Loan $loan): string
    {
        if ($loan->amount()->greaterThan(Money::PLN(2000))) {
            return Money::PLN(120)->getAmount();
        }

        throw InvalidArgumentException::feeIsInvalid();
    }
}
