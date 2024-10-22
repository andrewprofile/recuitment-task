<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Unit\Fee\Fake;

use PragmaGoTech\Interview\Fee\FeeCalculator;
use PragmaGoTech\Interview\Fee\Model\Exception\InvalidArgumentException;
use PragmaGoTech\Interview\Fee\Model\Loan;

final class LinearInterpolatedFeeCalculatorFake implements FeeCalculator
{
    public function calculate(Loan $loan): float
    {
        if ($loan->amount()->amount() > 2000.00) {
            return 115.00;
        }

        throw InvalidArgumentException::feeIsInvalid();
    }
}
