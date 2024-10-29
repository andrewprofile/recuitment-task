<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Unit\Fee\Model;

use PragmaGoTech\Interview\Fee\Model\Amount;
use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Fee\Model\Exception\InvalidArgumentException;

final class AmountTest extends TestCase
{
    public function testValidAmount(): void
    {
        $amount = new Amount(1500.0);

        $this->assertEquals(1500.0, $amount->amount());
    }

    public function testAmountTooLow(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The loan amount is out of the range.');

        new Amount(999.0);
    }

    public function testAmountTooHigh(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The loan amount is out of the range.');

        new Amount(20001.0);
    }
}
