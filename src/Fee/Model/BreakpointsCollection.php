<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Fee\Model;

use PragmaGoTech\Interview\Fee\CsvFileLoader;
use PragmaGoTech\Interview\Fee\Model\Exception\InvalidArgumentException;

/**
 * @template TKey of int
 * @template-covariant TValue
 */
class BreakpointsCollection implements Collection
{
    private const int AMOUNT_COLUMN = 0;
    private const int FEE_COLUMN = 1;

    /**
     * @param array<int, mixed> $breakpoints
     */
    public function __construct(private array $breakpoints = []) {}

    public function loadFromCsv(int $term, CsvFileLoader $generator): void
    {
        $this->breakpoints[$term] = static fn(): \Generator => $generator->load();
    }

    public function loadByTerm(int $term): object
    {
        return new Breakpoints($this->eager($term)->all());
    }

    /**
     * @return array<TKey, array<string>>
     */
    private function get(int $key): array
    {
        if (!array_key_exists($key, $this->all())) {
            throw InvalidArgumentException::termIsInvalid();
        }

        $breakpoints = $this->all()[$key];

        return is_array($breakpoints) ? $breakpoints : iterator_to_array($breakpoints());
    }

    /**
     * @return array<int, TValue>
     */
    public function all(): array
    {
        return $this->breakpoints;
    }

    /**
     * @return BreakpointsCollection<int, Breakpoint>
     */
    private function eager(int $term): self
    {
        $breakpoints = $this->get($term);
        $collection = [];

        foreach ($breakpoints as $breakpoint) {
            $collection[] = new Breakpoint(
                new Amount((float)$breakpoint[self::AMOUNT_COLUMN]),
                new Fee((float)$breakpoint[self::FEE_COLUMN])
            );
        }

        return new self($collection);
    }
}
