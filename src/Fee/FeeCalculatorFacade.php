<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Fee;

use Money\Money;
use PragmaGoTech\Interview\Fee\Model\Loan;
use PragmaGoTech\Interview\Fee\Model\Term;

final readonly class FeeCalculatorFacade
{
    protected FeeCalculatorCollector $collector;

    public function __construct(
        AccurateFeeCalculator $accurateFeeCalculator,
        LinearInterpolatedFeeCalculator $linearInterpolatedFeeCalculator
    ) {
        $this->collector = new FeeCalculatorCollector([
            $accurateFeeCalculator,
            $linearInterpolatedFeeCalculator,
        ]);
    }

    public function calculate(int $term, int $amount): string
    {
        return $this->collector->calculate(new Loan(
            new Term($term),
            Money::PLN($amount)
        ));
    }
}
