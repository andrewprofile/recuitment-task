<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Fee;

use PragmaGoTech\Interview\Fee\Model\Breakpoints;
use PragmaGoTech\Interview\Fee\Model\Collection;
use PragmaGoTech\Interview\Fee\Model\Exception\InvalidArgumentException;
use PragmaGoTech\Interview\Fee\Model\Loan;

final readonly class LinearInterpolatedFeeCalculator implements FeeCalculator
{
    public function __construct(private Collection $breakpoints) {}

    public function calculate(Loan $loan): float
    {
        $term = $loan->term()->term();
        $breakpoints = $this->breakpoints->loadByTerm($term);

        $amount = $loan->amount()->amount();
        $interpolatedFee = $this->interpolatedFee($breakpoints, $amount);

        return $this->adjustFee($amount, $interpolatedFee);
    }

    private function adjustFee(float $amount, float $fee): float
    {
        $total = $amount + $fee;
        if ($total % 5 !== 0) {
            $fee += (5 - ($total % 5));
        }

        return round($fee, 2);
    }

    private function interpolatedFee(object $breakpoints, float $amount): float
    {
        // @codeCoverageIgnoreStart
        if (!($breakpoints instanceof Breakpoints)) {
            throw InvalidArgumentException::feeIsInvalid();
        }
        // @codeCoverageIgnoreEnd

        $lowerBound = $breakpoints->lowerBoundAmount($amount);
        $upperBound = $breakpoints->upperBoundAmount($amount);
        $lowerFee = $breakpoints->lowerFee($lowerBound);
        $upperFee = $breakpoints->upperFee($upperBound);

        $interpolatedFee = $lowerFee + (($amount - $lowerBound) /
                ($upperBound - $lowerBound)) * ($upperFee - $lowerFee);

        return ceil($interpolatedFee);
    }
}
