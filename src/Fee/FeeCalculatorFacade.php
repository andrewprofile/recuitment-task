<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Fee;

use PragmaGoTech\Interview\Fee\Model\Amount;
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

    public function calculate(int $term, float $amount): float
    {
        return $this->collector->calculate(new Loan(
            new Term($term),
            new Amount($amount)
        ));
    }
}
