<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Fee\Model;

interface Collection
{
    public function loadByTerm(int $term): object;
}
