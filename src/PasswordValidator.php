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

    /**
     * Validate password strong.
     *
     * @param string $attribute
     * @param string $value
     * @param array $parameters
     * @param \Illuminate\Validation\Validator $validator
     * @return bool
     */
    public function validatePasswordStrength($attribute, $value, $parameters, $validator)
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
        $this->createMessage($result, ' have 8 characters at least', '');
        if (in_array('uppercase', $parts)) {
            $uppercaseResult = $this->checkExistsUppercaseLetters($value);
            $this->createMessage($uppercaseResult, '  one A-Z characters');
            $result = $result && $uppercaseResult;
        }
        if (in_array('lowercase', $parts)) {
            $lowercaseResult = $this->checkExistsLowercaseLetters($value);
            $this->createMessage($lowercaseResult, '  one a-z characters');
            $result = $result && $lowercaseResult;
        }
        if (in_array('number', $parts)) {
            $numberResult = $this->checkExistsNumbers($value);
            $this->createMessage($numberResult, '  one 0-9 characters');
            $result = $result && $numberResult;
        }
        if (in_array('symbol', $parts)) {
            $symbolResult = $this->checkExistsSpecialCharacters($value);
            $this->createMessage($symbolResult, '  one special characters exp: @$!%*#?&');
            $result = $result && $symbolResult;
        }

        $validationMessages['password_strength'] = 'password must: ' . $this->password_strength_message;
        $this->setCustomMessages($validationMessages);
        return $result;

    }

    private function createMessage($result, string $message, string $prefix = ' have at least'): void
    {
        if (!$result) {
            if (empty($this->password_strength_message)) $this->password_strength_message .= $prefix;
            $this->password_strength_message .= $message;
        }
    }

    private function checkLength($value): bool
    {
        return strlen($value) >= $this->passwordLength;
    }

    private function checkExistsLowercaseLetters($value): bool
    {
        return preg_match("/[a-z]/u", $value);
    }

    private function checkExistsUppercaseLetters($value): bool
    {
        return preg_match("/[A-Z]/u", $value);
    }

    private function checkExistsNumbers($value): bool
    {
        return preg_match("/[0-9]/u", $value);
    }

    private function checkExistsSpecialCharacters($value): bool
    {
        return preg_match('/\p{Z}|\p{S}|\p{P}/u', $value);
    }
}
