<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Unit\Fee;

use Money\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Fee\FeeCalculator;
use PragmaGoTech\Interview\Fee\FeeCalculatorCollector;
use PragmaGoTech\Interview\Fee\Model\Exception\InvalidArgumentException;
use PragmaGoTech\Interview\Fee\Model\Loan;
use PragmaGoTech\Interview\Fee\Model\Term;
use PragmaGoTech\Interview\Unit\Fee\Fake\AccurateFeeCalculatorFake;
use PragmaGoTech\Interview\Unit\Fee\Fake\LinearInterpolatedFeeCalculatorFake;

final class FeeCalculatorCollectorTest extends TestCase
{
    public static function dataProvider(): \Generator
    {
        yield [24, 1000, '70'];
        yield [24, 2750, '120'];
    }

    /**
     * @throws Exception
     */
    #[DataProvider('dataProvider')]
    public function testCalculateReturnsCorrectFee(int $term, int $amount, string $fee): void
    {
        $feeCalculatorMock = $this->createMock(FeeCalculator::class);
        $term = new Term($term);
        $amount = Money::PLN($amount);
        $loan = new Loan($term, $amount);

        $feeCalculatorMock->method('calculate')->willReturn($fee);

        $collector = new FeeCalculatorCollector([$feeCalculatorMock]);

        $result = $collector->calculate($loan);

        $this->assertEquals($fee, $result);

        $collector = new FeeCalculatorCollector([
            new AccurateFeeCalculatorFake(),
            new LinearInterpolatedFeeCalculatorFake()
        ]);

        $result = $collector->calculate($loan);

        $this->assertEquals($fee, $result);
    }

    public function testCalculateThrowsExceptionWhenNoFeeIsCalculated(): void
    {
        $term = new Term(24);
        $amount = Money::PLN(1000);
        $loan = new Loan($term, $amount);

        $collector = new FeeCalculatorCollector([]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The fee must be a positive float.');

        $collector->calculate($loan);

        $amount = Money::PLN(1005);
        $loan = new Loan($term, $amount);

        $collector = new FeeCalculatorCollector([
            new AccurateFeeCalculatorFake(),
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The fee must be a positive float.');

        $collector->calculate($loan);
    }
}
