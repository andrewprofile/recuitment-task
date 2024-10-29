<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Unit\Fee\Fake;

use PragmaGoTech\Interview\Fee\FeeCalculator;
use PragmaGoTech\Interview\Fee\Model\Fee;
use PragmaGoTech\Interview\Fee\Model\Loan;

final class AccurateFeeCalculatorFake implements FeeCalculator
{
    public function calculate(Loan $loan): float
    {
        if ($loan->amount()->amount() === 1000.00) {
            return 70.00;
        }

        return Fee::CONTINUE_PROPAGATION;
    }
}
