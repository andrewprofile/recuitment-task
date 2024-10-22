<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Fee;

final class CsvFileLoader
{
    private \SplFileObject $file;

    public function __construct(string $filePath)
    {
        $this->file = new \SplFileObject($filePath, 'rb');
        $this->file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY);
    }

    public function load(): \Generator
    {
        $isHeader = true;
        foreach ($this->file as $line) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }

            yield $line;
        }

        $this->file->rewind();
    }
}
