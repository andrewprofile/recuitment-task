PragmaGO.TECH Interview Test - Fee Calculation
=====

## Background

This test is designed to evaluate your problem solving approach and your engineering ability. Design your solution in a way that shows your knowledge of OOP concepts, SOLID principles, design patterns, clean and extensible architecture.

Provide a test suite verifying your solution, use any testing framework you feel comfortable with. Use any libraries (or none) you feel add value to your solution. Treat the packaged project as a template; if you feel that your solution can be improved with modifications to it then please go ahead.

## The test

The requirement is to build a fee calculator that - given a monetary **amount** and a **term** (the contractual duration of the loan, expressed as a number of months) - will produce an appropriate fee for a loan, based on a fee structure and a set of rules described below. A general contract for this functionality is defined in the interface `FeeCalculator`.

Implement your solution such that it fulfils the requirements.

- The fee structure does not follow a formula.
- Values in between the breakpoints should be interpolated linearly between the lower bound and upper bound that they fall between.
- The number of breakpoints, their values, or storage might change.
- The term can be either 12 or 24 (number of months), you can also assume values will always be within this set.
- The fee should be rounded up such that fee + loan amount is an exact multiple of 5.
- The minimum amount for a loan is 1,000 PLN, and the maximum is 20,000 PLN.
- You can assume values will always be within this range but they may be any value up to 2 decimal places.

Example inputs/outputs:
|Loan amount  |Term       |Fee     |
|-------------|-----------|--------|
|11,500 PLN   |24 months  |460 PLN |
|19,250 PLN   |12 months  |385 PLN |

# Installation
A database or any other external dependency is not required for this test.

```bash
composer install
```

# Example

```php
<?php

use PragmaGoTech\Interview\Fee\AccurateFeeCalculator;
use PragmaGoTech\Interview\Fee\CsvFileLoader;
use PragmaGoTech\Interview\Fee\FeeCalculatorFacade;
use PragmaGoTech\Interview\Fee\LinearInterpolatedFeeCalculator;
use PragmaGoTech\Interview\Fee\Model\BreakpointsCollection;

$resourcesDir = __DIR__ . DIRECTORY_SEPARATOR . 'resources';
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
$facade = new FeeCalculatorFacade(
    $accurateFeeCalculator,
    $linearInterpolatedFeeCalculator,
);
$fee = $facade->calculate(24, 2750);
// $fee = (float) 115.0
```

# Fee Structure
The fee structure doesn't follow particular algorithm and it is possible that same fee will be applicable for different amounts.

### Term 12
```
1000 PLN: 50 PLN
2000 PLN: 90 PLN
3000 PLN: 90 PLN
4000 PLN: 115 PLN
5000 PLN: 100 PLN
6000 PLN: 120 PLN
7000 PLN: 140 PLN
8000 PLN: 160 PLN
9000 PLN: 180 PLN
10000 PLN: 200 PLN
11000 PLN: 220 PLN
12000 PLN: 240 PLN
13000 PLN: 260 PLN
14000 PLN: 280 PLN
15000 PLN: 300 PLN
16000 PLN: 320 PLN
17000 PLN: 340 PLN
18000 PLN: 360 PLN
19000 PLN: 380 PLN
20000 PLN: 400 PLN
```

### Term 24

```
1000 PLN: 70 PLN
2000 PLN: 100 PLN
3000 PLN: 120 PLN
4000 PLN: 160 PLN
5000 PLN: 200 PLN
6000 PLN: 240 PLN
7000 PLN: 280 PLN
8000 PLN: 320 PLN
9000 PLN: 360 PLN
10000 PLN: 400 PLN
11000 PLN: 440 PLN
12000 PLN: 480 PLN
13000 PLN: 520 PLN
14000 PLN: 560 PLN
15000 PLN: 600 PLN
16000 PLN: 640 PLN
17000 PLN: 680 PLN
18000 PLN: 720 PLN
19000 PLN: 760 PLN
20000 PLN: 800 PLN
```

# In Production

Working with financial data is a serious matter, and small rounding mistakes in an application may lead to serious consequences in real life. That's why floating-point arithmetic is not suited for monetary calculations. 
However, for the purpose of this example I do not use external libraries, because as I understood it is to show the general concepts of programming and not how I am able to use libraries, so in a production environment it would be advisable to use for example the library `moneyphp/money`. See [#2](https://github.com/andrewprofile/recuitment-task/pull/2).

# Useful commands

Run the following command to run the test suite. 

```bash
composer test
```

Run the following command to run the coverage code.

```bash
composer code-coverage
```

Run the following command to run the static analysis code.

```bash
composer static-analyze
```
