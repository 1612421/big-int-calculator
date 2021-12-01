<?php


namespace App\Services;


use App\Models\BaseBigNumber;
use App\Services\Interfaces\ICalculatorService;

class CalculatorService implements ICalculatorService
{
    public function calculate(BaseBigNumber $firstNum, BaseBigNumber $secondNum, string $operator): string
    {
        switch ($operator) {
            case '+':
                return $firstNum->add($secondNum);
            case '-':
                return $firstNum->sub($secondNum);
            case '*':
                return $firstNum->multiply($secondNum);
            default:
                return 'NaN';
        }
    }
}
