<?php


namespace App\Models;


use Exception;
use Nette\NotImplementedException;

abstract class BaseBigNumber
{
    #region Properties

    protected string $mRegexNumber;
    protected string $mValue;

    #endregion

    #region Getter and Setter

    /**
     * @throws Exception
     */
    public function setValue(string $value): BaseBigNumber {
        if (preg_match($this->mRegexNumber, $value)) {
            $this->mValue = $value;
        } else {
            throw new Exception('Invalid value');
        }

        return $this;
    }

    public function getValue(): string {
        return $this->mValue;
    }

    #endregion

    #region Constructors

    /**
     * @throws Exception
     */
    public function __construct(string $value) {
        if (!preg_match($this->mRegexNumber, $value)) {
            throw new Exception('Not a number');
        }

        $this->mValue = $value;
    }

    #endregion

    #region Methods

    public function add(BaseBigNumber $secondNumber): string {
        throw new NotImplementedException();
    }
    public function sub(BaseBigNumber $secondNumber): string {
        throw new NotImplementedException();
    }
    public function multiply(BaseBigNumber $secondNumber): string {
        throw new NotImplementedException();
    }

    #endregion
}
