<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Fee\Model;

/**
 * A cut down version of a loan application containing
 * only the required properties for this test.
 */
final readonly class Loan
{
    public function __construct(private Term $term, private Amount $amount) {}

    /**
     * Term (loan duration) for this loan application
     * in number of months.
     */
    public function term(): Term
    {
        return $this->term;
    }

    /**
     * Amount requested for this loan application.
     */
    public function amount(): Amount
    {
        return $this->amount;
    }
}
