<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Fee;

use Money\Money;
use PragmaGoTech\Interview\Fee\Model\Breakpoints;
use PragmaGoTech\Interview\Fee\Model\Collection;
use PragmaGoTech\Interview\Fee\Model\Exception\InvalidArgumentException;
use PragmaGoTech\Interview\Fee\Model\Fee;
use PragmaGoTech\Interview\Fee\Model\Loan;

final readonly class AccurateFeeCalculator implements FeeCalculator
{
    public function __construct(private Collection $breakpoints) {}

    public function accurateFee(object $breakpoints, Money $amount): Money
    {
        // @codeCoverageIgnoreStart
        if (!($breakpoints instanceof Breakpoints)) {
            throw InvalidArgumentException::feeIsInvalid();
        }
        // @codeCoverageIgnoreEnd

        return $breakpoints->fee($amount) ?? Money::PLN(Fee::CONTINUE_PROPAGATION);
    }
    public function calculate(Loan $loan): string
    {
        $term = $loan->term()->term();
        $breakpoints = $this->breakpoints->loadByTerm($term);
        $amount = $loan->amount();

        return $this->accurateFee($breakpoints, $amount)->getAmount();
    }
}
