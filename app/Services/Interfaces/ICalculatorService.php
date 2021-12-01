<?php

namespace App\Services\Interfaces;

use App\Models\BaseBigNumber;

interface ICalculatorService {
    function calculate(BaseBigNumber $firstNum, BaseBigNumber $secondNum, string $operator): string;
}
