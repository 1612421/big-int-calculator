<?php

namespace App\Http\Controllers;

use App\Models\BigInteger;
use App\Services\Interfaces\ICalculatorService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    protected string $mCalculationRegex = '/^(\-?[0-9]+) ?(\+|\-|\*) ?(\-?[0-9]+)$/';
    protected ICalculatorService $mCalculatorService;

    public function __construct(ICalculatorService $calculatorService)
    {
        $this->mCalculatorService = $calculatorService;
    }

    public function index() {
        return view('calculator');
    }

    /**
     * @throws Exception
     */
    public function calculate(Request $request): JsonResponse {
        $validators = $request->validate([
           'calculation' => ['required', 'string', "regex:{$this->mCalculationRegex}"]
        ]);
        $calculation = $validators['calculation'];
        preg_match($this->mCalculationRegex, $calculation, $matches);
        $firstNum = new BigInteger($matches[1]);
        $operator = $matches[2];
        $secondNum = new BigInteger($matches[3]);
        $result = $this->mCalculatorService->calculate($firstNum, $secondNum, $operator);

        return response()->json([
           'status_code' => 200,
           'data' => [
               'result' => $result,
           ],
        ]);
    }
}
