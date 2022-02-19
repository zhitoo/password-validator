<?php

namespace Hshafiei374\PasswordValidator;

use Illuminate\Validation\Validator;

class PasswordValidator extends Validator
{
    private $passwordLength = 6;
    public $password_strength_message;

    public function __construct($translator, $data, $rules, $messages = [], $customAttributes = [])
    {
        parent::__construct($translator, $data, $rules, $messages, $customAttributes);
    }


    public function validateHaveNumber($attribute, $value, $parameters, $validator)
    {
        return $this->checkExistsNumbers($value) > ($parameters[0] ?? 1);
    }

    public function validateHaveUppercase($attribute, $value, $parameters, $validator)
    {
        return $this->checkExistsUppercaseLetters($value) > ($parameters[0] ?? 1);
    }

    public function validateHaveLowercase($attribute, $value, $parameters, $validator)
    {
        return $this->checkExistsLowercaseLetters($value) > ($parameters[0] ?? 1);
    }

    public function validateHaveSymbol($attribute, $value, $parameters, $validator)
    {
        return $this->checkExistsSpecialCharacters($value) > ($parameters[0] ?? 1);
    }

    /**
     * Validate password strong.
     *
     * @param string $attribute
     * @param string $value
     * @param array $parameters
     * @param \Illuminate\Validation\Validator $validator
     * @return bool
     */
    public function validateHaveStrength($attribute, $value, $parameters, $validator)
    {
        $paramOne = $parameters[0] ?? 5;
        if (is_numeric($paramOne)) {
            $paramOne = intval($paramOne);
            switch ($paramOne) {
                case 1:
                    $paramOne = '';//length only
                    break;
                case 2:
                    $paramOne = 'lowercase';
                    break;
                case 3:
                    $paramOne = 'lowercase-uppercase';
                    break;
                case 4:
                    $paramOne = 'lowercase-uppercase-number';
                    break;
                default:
                    $paramOne = 'lowercase-uppercase-number-symbol';
                    break;
            }
        }
        if (isset($parameters[1]) and is_numeric($parameters[1])) {
            $this->passwordLength = intval($parameters[1]);
        }
        $parts = explode('-', $paramOne);

        //length is base validation and check always
        $result = $this->checkLength($value);
        $this->createMessage($result, trans('password_validator::password_validator.have_n_characters_at_least', [
            'n' => $this->passwordLength
        ]), '');
        if (in_array('uppercase', $parts)) {
            $uppercaseResult = $this->checkExistsUppercaseLetters($value) > 0;
            $this->createMessage($uppercaseResult, trans('password_validator::password_validator.one_A-Z_characters'));
            $result = $result && $uppercaseResult;
        }
        if (in_array('lowercase', $parts)) {
            $lowercaseResult = $this->checkExistsLowercaseLetters($value) > 0;
            $this->createMessage($lowercaseResult, trans('password_validator::password_validator.one_a-z_characters'));
            $result = $result && $lowercaseResult;
        }
        if (in_array('number', $parts)) {
            $numberResult = $this->checkExistsNumbers($value) > 0;
            $this->createMessage($numberResult, trans('password_validator::password_validator.one_0-9_characters'));
            $result = $result && $numberResult;
        }
        if (in_array('symbol', $parts)) {
            $symbolResult = $this->checkExistsSpecialCharacters($value) > 0;
            $this->createMessage($symbolResult, trans('password_validator::password_validator.one_special_characters'));
            $result = $result && $symbolResult;
        }

        $validationMessages['have_strength'] = trans('password_validator::password_validator.password_must') . $this->password_strength_message;
        $this->setCustomMessages($validationMessages);
        return $result;

    }

    private function createMessage($result, string $message, string $prefix = ' have at least'): void
    {
        if ($prefix == ' have at least') {
            $prefix = trans('password_validator::password_validator.have_at_least');
        }
        if (!$result) {
            if (empty($this->password_strength_message)) $this->password_strength_message .= $prefix;
            $this->password_strength_message .= $message;
        }
    }

    private function checkLength($value): bool
    {
        return strlen($value) >= $this->passwordLength;
    }

    private function checkExistsLowercaseLetters($value): int
    {
        return preg_match_all("/[a-z]/u", $value);
    }

    private function checkExistsUppercaseLetters($value): int
    {
        return preg_match_all("/[A-Z]/u", $value);
    }

    private function checkExistsNumbers($value): int
    {
        return preg_match_all("/[0-9]/u", $value);
    }

    private function checkExistsSpecialCharacters($value): int
    {
        return preg_match_all('/\p{Z}|\p{S}|\p{P}/u', $value);
    }
}
