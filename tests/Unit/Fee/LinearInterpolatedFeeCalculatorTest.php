<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Unit\Fee;

use Money\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PragmaGoTech\Interview\Fee\FeeCalculator;
use PragmaGoTech\Interview\Fee\LinearInterpolatedFeeCalculator;
use PHPUnit\Framework\TestCase;
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
                [1000, 70],
                [2000, 100],
                [3000, 120],
                [11000, 440],
                [12000, 480],
            ],
        ]);
        $this->feeCalculator = new LinearInterpolatedFeeCalculator($breakpointsCollection);
    }

    public static function dataProvider(): \Generator
    {
        yield [24, 11500, 440];
        yield [24, 2750, 120];
    }

    #[DataProvider('dataProvider')]
    public function testCalculateTotalFeeWithInterpolatedValues(int $term, int $amount, int $fee): void
    {
        $term = new Term($term);
        $amount = Money::PLN($amount);
        $loan = new Loan($term, $amount);

        $result = $this->feeCalculator->calculate($loan);

        $this->assertEquals($fee, $result);
    }

    public function testCalculateTotalFeeAdjustsToNearestFive(): void
    {
        $term = new Term(24);
        $amount = Money::PLN(2755);
        $loan = new Loan($term, $amount);

        $result = $this->feeCalculator->calculate($loan);

        $this->assertEquals(120, $result);
    }
}
