<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Unit\Fee;

use Money\Money;
use PragmaGoTech\Interview\Fee\AccurateFeeCalculator;
use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Fee\FeeCalculator;
use PragmaGoTech\Interview\Fee\Model\Exception\InvalidArgumentException;
use PragmaGoTech\Interview\Fee\Model\Loan;
use PragmaGoTech\Interview\Fee\Model\BreakpointsCollection;
use PragmaGoTech\Interview\Fee\Model\Term;

final class AccurateFeeCalculatorTest extends TestCase
{
    private FeeCalculator $feeCalculator;

    protected function setUp(): void
    {
        $breakpointsCollection = new BreakpointsCollection([
            24 => [
                [1000, 70],
            ],
        ]);
        $this->feeCalculator = new AccurateFeeCalculator($breakpointsCollection);
    }

    public function testCalculateTotalFeeWithExactValues(): void
    {
        $term = new Term(24);
        $amount = Money::PLN(1000);
        $loan = new Loan($term, $amount);

        $result = $this->feeCalculator->calculate($loan);

        $this->assertEquals(70.00, $result);
    }

    public function testCalculateThrowsExceptionWhenNoTermDefined(): void
    {
        $term = new Term(12);
        $amount = Money::PLN(1000);
        $loan = new Loan($term, $amount);

        $this->expectException(InvalidArgumentException::class);

        $this->feeCalculator->calculate($loan);
    }

    public function testCalculateTotalFeeWithNotExactValues(): void
    {
        $term = new Term(24);
        $amount = Money::PLN(1005);
        $loan = new Loan($term, $amount);

        $result = $this->feeCalculator->calculate($loan);
        $this->assertEquals(-1.00, $result);
    }
}
