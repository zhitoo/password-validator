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
        $strong = 1;
        if (isset($parameters[0])) {
            $strong = intval($parameters[0]);
            if ($strong > 6) {
                $this->passwordLength = $strong;
            }
        }
        if (isset($parameters[1])) {
            $this->passwordLength = intval($parameters[1]);
        }
        switch ($strong) {
            case 1:
                $result = $this->strongOne($value);
                break;
            case 2:
                $result = $this->strongTwo($value);
                break;
            case 3:
                $result = $this->strongThree($value);
                break;
            case 4:
                $result = $this->strongFour($value);
                break;
            default:
                $result = $this->strongFive($value);
                break;
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

    private function strongOne($value): bool
    {
        $result = $this->checkLength($value);
        $this->createMessage($result, ' have 8 characters at least', '');
        return $result;
    }

    private function strongTwo($value): bool
    {
        $result = $this->checkExistsLowercaseLetters($value);
        $pervResult = $this->strongOne($value);
        $this->createMessage($result, '  one a-z characters');
        return $result and $pervResult;
    }

    private function strongThree($value): bool
    {
        $result = $this->checkExistsUppercaseLetters($value);
        $pervResult = $this->strongTwo($value);
        $this->createMessage($result, '  one A-Z characters');
        return $result and $pervResult;
    }

    private function strongFour($value): bool
    {
        $result = $this->checkExistsNumbers($value);
        $pervResult = $this->strongThree($value);
        $this->createMessage($result, '  one 0-9 characters');
        return $result and $pervResult;
    }

    private function strongFive($value): bool
    {
        $result = $this->checkExistsSpecialCharacters($value);
        $pervResult = $this->strongFour($value);
        $this->createMessage($result, '  one special character exp: @$!%*#?&');
        return $result and $pervResult;
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
