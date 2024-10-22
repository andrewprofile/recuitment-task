<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Integration\Fee;

use PHPUnit\Framework\Attributes\DataProvider;
use PragmaGoTech\Interview\Fee\AccurateFeeCalculator;
use PragmaGoTech\Interview\Fee\CsvFileLoader;
use PragmaGoTech\Interview\Fee\FeeCalculatorFacade;
use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Fee\LinearInterpolatedFeeCalculator;
use PragmaGoTech\Interview\Fee\Model\Exception\InvalidArgumentException;
use PragmaGoTech\Interview\Fee\Model\BreakpointsCollection;

class FeeCalculatorFacadeTest extends TestCase
{
    private FeeCalculatorFacade $feeCalculatorFacade;

    protected function setUp(): void
    {
        $resourcesDir = __DIR__. DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resources';
        $breakpointsCollection = new BreakpointsCollection();
        $breakpointsCollection->loadFromCsv(
            12,
            (new CsvFileLoader($resourcesDir . DIRECTORY_SEPARATOR . '12.csv'))
        );
        $breakpointsCollection->loadFromCsv(
            24,
            (new CsvFileLoader($resourcesDir . DIRECTORY_SEPARATOR . '24.csv'))
        );
        $accurateFeeCalculator = new AccurateFeeCalculator($breakpointsCollection);
        $linearInterpolatedFeeCalculator = new LinearInterpolatedFeeCalculator($breakpointsCollection);

        $this->feeCalculatorFacade = new FeeCalculatorFacade(
            $accurateFeeCalculator,
            $linearInterpolatedFeeCalculator
        );
    }

    public static function dataProvider(): \Generator
    {
        yield [24, 2750.00, 115.00];
        yield [24, 11500.00, 460.00];
        yield [12, 19250.00, 385.00];
    }

    #[DataProvider('dataProvider')]
    public function testCalculateReturnsExpectedFee(int $term, float $amount, float $fee): void
    {
        $this->assertEquals($fee, $this->feeCalculatorFacade->calculate($term, $amount));
    }

    public function testCalculateThrowsExceptionWhenNoFeeIsCalculated(): void
    {
        $term = 13;
        $amount = 1000.0;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The loan terms should be defined.');

        $this->feeCalculatorFacade->calculate($term, $amount);
    }
}
