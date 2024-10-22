<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Fee;

use PragmaGoTech\Interview\Fee\Model\Loan;

interface FeeCalculator
{
    public function calculate(Loan $loan): float;
}
