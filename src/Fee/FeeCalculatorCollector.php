<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Fee;

use PragmaGoTech\Interview\Fee\Model\Exception\InvalidArgumentException;
use PragmaGoTech\Interview\Fee\Model\Fee;
use PragmaGoTech\Interview\Fee\Model\Loan;

final readonly class FeeCalculatorCollector
{
    /**
     * @param FeeCalculator[] $feeCalculators
     */
    public function __construct(private array $feeCalculators) {}

    /**
     * @return string The calculated total fee.
     */
    public function calculate(Loan $loan): string
    {
        $fee = Fee::CONTINUE_PROPAGATION;

        if (empty($this->feeCalculators)) {
            throw InvalidArgumentException::feeIsInvalid();
        }

        foreach ($this->feeCalculators as $feeCalculator) {
            $fee = $feeCalculator->calculate($loan);

            if ($fee > 0) {
                break;
            }
        }

        return $fee > 0 ? $fee : throw InvalidArgumentException::feeIsInvalid();
    }
}
