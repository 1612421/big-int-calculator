<?php


namespace App\Models;


use App\Constants;
use Exception;
use Nette\NotImplementedException;
use phpDocumentor\Reflection\Types\This;

class BigInteger extends BaseBigNumber
{

    #region Constructors

    public function __construct(string $value)
    {
        $this->mRegexNumber = '/^-?[0-9]+$/';
        $this->mType = Constants::INT_TYPE;
        $value = $this->normalize($value);

        parent::__construct($value);
    }

    #endregion

    #region Methods

    protected function normalize(string $number): string
    {
        $isNegative = $number[0] == '-';

        if ($isNegative) {
            $number = substr($number, 1);
        }

        $number = ltrim($number, '0');

        if (empty($number)) {
            return '0';
        }

        return $isNegative ? '-' . $number : $number;
    }

    /** See if the first number is smaller than the second number.
     * -1: false
     * 1: true
     * 0: equal
     * @param string $firstNum
     * @param string $secondNum
     * @return int
     */
    public function isSmaller(string $firstNum, string $secondNum): int
    {
        $n1 = strlen($firstNum);
        $n2 = strlen($secondNum);

        if ($n1 > $n2) {
            return -1;
        }

        if ($n1 < $n2) {
            return 1;
        }

        for ($i = 0; $i < $n1; $i++) {
            if ($firstNum[$i] < $secondNum[$i]) {
                return 1;
            }

            if ($firstNum[$i] > $secondNum[$i]) {
                return -1;
            }
        }

        return 0;
    }

    /** Summation of two numbers.
     * Accept: -a + -b, -a + b, a + -b, a + b
     * @param BaseBigNumber $secondNumber
     * @return string
     * @throws Exception
     */
    public function add(BaseBigNumber $secondNumber): string
    {
        // Not implement other big number type
        if ($secondNumber->mType != Constants::INT_TYPE) {
            return 'NaN';
        }

        $firstNum = $this->mValue;
        $secondNum = $secondNumber->mValue;
        $isPositive1 = $firstNum[0] != '-';
        $isPositive2 = $secondNum[0] != '-';
        $hasNegativeResult = !$isPositive1 && !$isPositive2;

        if (!$hasNegativeResult) {
            // -a + b = b - a
            if (!$isPositive1) {
                $newFirstNumber = new BigInteger(substr($secondNumber->mValue, 1));
                return $secondNumber->sub($newFirstNumber);
            }

            // a + -b = a - b
            if (!$isPositive2) {
                $newSecondNumber = new BigInteger(substr($this->mValue, 1));
                return $this->sub($newSecondNumber);
            }
        } else {
            $firstNum = substr($firstNum, 1);
            $secondNum = substr($secondNum, 1);
        }

        $result = '';

        if ($firstNum == '0') {
            $result = $secondNum;
        } else if ($secondNum == '0') {
            $result = $firstNum;
        } else {
            $length1 = strlen($firstNum);
            $length2 = strlen($secondNum);

            if ($length1 < $length2) {
                // swap two numbers
                $temp = $firstNum;
                $firstNum = $secondNum;
                $secondNum = $temp;

                // swap two lengths of two numbers
                $length1 = $length1 + $length2;
                $length2 = $length1 - $length2;
                $length1 = $length1 - $length2;
            }

            $secondNum = str_pad($secondNum, $length1, '0', STR_PAD_LEFT);
            $carry = 0;

            for ($i = $length1 - 1; $i > -1; $i--) {
                $sum = $firstNum[$i] + $secondNum[$i] + $carry;
                $carry = floor($sum / 10);
                $result = ($sum % 10) . $result;
            }

            if ($carry) {
                $result = 1 . $result;
            }
        }

        if (!$result[0]) {
            return '0';
        }

        return $hasNegativeResult ? '-' . $result : $result;
    }

    /** Subtraction of two number.
     * Accept: -a - -b, -a - b, a - -b, a - b
     * @param BaseBigNumber $secondNumber
     * @return string
     * @throws Exception
     */
    public function sub(BaseBigNumber $secondNumber): string
    {
        // Not implement other big number type
        if ($secondNumber->mType != Constants::INT_TYPE) {
            return 'NaN';
        }

        $strFirstNum = $this->mValue;
        $strSecondNum = $secondNumber->mValue;
        $isNegative1 = $strFirstNum[0] == '-';
        $isNegative2 = $strSecondNum[0] == '-';

        // -a - -b = b - a
        if ($isNegative1 && $isNegative2) {
            return $secondNumber->sub($this);
        }

        // -a - b = -a + -b
        if ($isNegative1 && !$isNegative2) {
            return $this->add($secondNumber);
        }

        // a - -b = a + b
        if (!$isNegative1 && $isNegative2) {
            $newSecondNumber = new BigInteger(substr($strSecondNum, 1));
            return $this->add($newSecondNumber);
        }

        // Here is a - b process

        // 0 - b = -b
        if ($strFirstNum == 0) {
            return '-' . $strSecondNum;
        }

        // a - 0 = a
        if ($strSecondNum == 0) {
            return $strFirstNum;
        }

        $hasNegativeResult = false;
        $isSmaller = $this->isSmaller($strFirstNum, $strSecondNum);

        // a - a = 0
        if ($isSmaller == 0) {
            return 0;
        }

        if ($this->isSmaller($strFirstNum, $strSecondNum) == 1) {
            $hasNegativeResult = true;
            $temp = $strFirstNum;
            $strFirstNum = $strSecondNum;
            $strSecondNum = $temp;
        }

        $length1 = strlen($strFirstNum);
        $strSecondNum = str_pad($strSecondNum, $length1, '0', STR_PAD_LEFT);
        $result = '';
        $carry = 0;

        for ($i = $length1 - 1; $i > -1; $i--) {
            $diff = $strFirstNum[$i] - $strSecondNum[$i] - $carry;

            if ($diff < 0) {
                $diff += 10;
                $carry = 1;
            } else {
                $carry = 0;
            }

            $result = $diff . $result;
        }

        $result = ltrim($result, 0);

        return $hasNegativeResult ? '-' . $result : $result;
    }

    public function multiply(BaseBigNumber $secondNumber): string
    {
        // Not implement other big number type
        if ($secondNumber->mType != Constants::INT_TYPE) {
            return 'NaN';
        }

        $isNegative1 = $this->mValue[0] == '-';
        $isNegative2 = $secondNumber->mValue[0] == '-';
        $strFirstNum = $isNegative1 ? substr($this->mValue, 1) : $this->mValue;
        $strSecondNum = $isNegative1 ? substr($secondNumber->mValue, 1) : $secondNumber->mValue;
        $hasNegativeResult = false;
        $result = '';

        if ($isNegative1 != $isNegative2) {
            $hasNegativeResult = true;
        }

        if ($strFirstNum == '0' || $strSecondNum == '0') {
            return 0;
        }

        if ($strFirstNum == '1') {
            $result = $strSecondNum;
        } else if ($strSecondNum == '1') {
            $result = $strFirstNum;
        } else {
            $length1 = strlen($strFirstNum);
            $length2 = strlen($strSecondNum);
            $indexDigit1 = 0;
            $stored = array_fill(0, $length1 + $length2, 0);

            for ($i = $length1 - 1; $i > -1; $i--) {
                $digit1 = $strFirstNum[$i];
                $indexDigit2 = 0;
                $carry = 0;

                for ($j = $length2 - 1; $j > -1; $j--) {
                    $digit2 = $strSecondNum[$j];
                    $sum = $digit1 * $digit2 + $stored[$indexDigit1 + $indexDigit2] + $carry;
                    $carry = (int)floor($sum / 10);
//                    dump($sum, $carry, $sum % 10);
                    $stored[$indexDigit1 + $indexDigit2] = $sum % 10;
                    $indexDigit2++;
                }

                $stored[$indexDigit1 + $indexDigit2] += $carry;
                $indexDigit1++;
            }

            $i = $length1 + $length2 - 1;

            while ($i > -1 && $stored[$i] == 0) {
                $i--;
            }

            while ($i > -1) {
                $result = $result . $stored[$i];
                $i--;
            }
        }

        return $hasNegativeResult ? '-' . $result : $result;
    }

    #endregion
}
