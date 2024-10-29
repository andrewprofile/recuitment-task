<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Unit\Fee;

use PHPUnit\Framework\Attributes\DataProvider;
use PragmaGoTech\Interview\Fee\FeeCalculator;
use PragmaGoTech\Interview\Fee\LinearInterpolatedFeeCalculator;
use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Fee\Model\Amount;
use PragmaGoTech\Interview\Fee\Model\BreakpointsCollection;
use PragmaGoTech\Interview\Fee\Model\Loan;
use PragmaGoTech\Interview\Fee\Model\Term;

final class LinearInterpolatedFeeCalculatorTest extends TestCase
{
    private FeeCalculator $feeCalculator;

    protected function setUp(): void
    {
        $breakpointsCollection = new BreakpointsCollection([
            24 => [
                [1000.00, 70.00],
                [2000.00, 100.00],
                [3000.00, 120.00],
                [11000.00, 440.00],
                [12000.00, 480.00],
            ],
        ]);
        $this->feeCalculator = new LinearInterpolatedFeeCalculator($breakpointsCollection);
    }

    public static function dataProvider(): \Generator
    {
        yield [24, 11500.00, 460.00];
        yield [24, 2750.00, 115.00];
    }

    #[DataProvider('dataProvider')]
    public function testCalculateTotalFeeWithInterpolatedValues(int $term, float $amount, float $fee): void
    {
        $term = new Term($term);
        $amount = new Amount($amount);
        $loan = new Loan($term, $amount);

        $result = $this->feeCalculator->calculate($loan);

        $this->assertEquals($fee, $result);
    }

    public function testCalculateTotalFeeAdjustsToNearestFive(): void
    {
        $term = new Term(24);
        $amount = new Amount(2755.00);
        $loan = new Loan($term, $amount);

        $result = $this->feeCalculator->calculate($loan);

        $this->assertEquals(120.00, $result);
    }
}
