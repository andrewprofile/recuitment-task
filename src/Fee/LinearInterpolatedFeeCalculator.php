<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Fee;

use Money\Money;
use PragmaGoTech\Interview\Fee\Model\Breakpoints;
use PragmaGoTech\Interview\Fee\Model\Collection;
use PragmaGoTech\Interview\Fee\Model\Exception\InvalidArgumentException;
use PragmaGoTech\Interview\Fee\Model\Loan;

final readonly class LinearInterpolatedFeeCalculator implements FeeCalculator
{
    public function __construct(private Collection $breakpoints) {}

    public function calculate(Loan $loan): string
    {
        $term = $loan->term()->term();
        $breakpoints = $this->breakpoints->loadByTerm($term);

        $amount = $loan->amount();
        return $this->interpolatedFee($breakpoints, $amount)->getAmount();
    }

    private function interpolatedFee(object $breakpoints, Money $amount): Money
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

        $amountStep = $upperBound->subtract($lowerBound);  // ($upperBound - $lowerBound)
        $feeStep = $upperFee->subtract($lowerFee); // ($upperFee - $lowerFee)
        $amountDelta = $amount->subtract($lowerBound); // ($amount - $lowerBound)

        // (($amount - $lowerBound) / ($upperBound - $lowerBound))
        $coefficient = $amountDelta->divide($amountStep->getAmount(), Money::ROUND_HALF_EVEN);
        $feeDelta = $feeStep->multiply($coefficient->getAmount(), Money::ROUND_HALF_EVEN);

        return $lowerFee->add($feeDelta);
    }
}
