<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Fee\Model\Exception;

final class InvalidArgumentException extends \InvalidArgumentException
{
    public static function amountOutOfRange(): self
    {
        return new self('The loan amount is out of the range.');
    }

    public static function feeIsInvalid(): self
    {
        return new self('The fee must be a positive float.');
    }

    public static function termOutOfRange(): self
    {
        return new self('Loan terms should be between 12 and 24.');
    }

    public static function termIsInvalid(): self
    {
        return new self('The loan terms should be defined.');
    }
}
