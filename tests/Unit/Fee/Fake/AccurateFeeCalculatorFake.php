<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Unit\Fee\Fake;

use Money\Money;
use PragmaGoTech\Interview\Fee\FeeCalculator;
use PragmaGoTech\Interview\Fee\Model\Fee;
use PragmaGoTech\Interview\Fee\Model\Loan;

final class AccurateFeeCalculatorFake implements FeeCalculator
{
    public function calculate(Loan $loan): string
    {
        if ($loan->amount()->equals(Money::PLN(1000))) {
            return Money::PLN(70)->getAmount();
        }

        return Money::PLN(Fee::CONTINUE_PROPAGATION)->getAmount();
    }
}
