<?php

namespace Hshafiei374\PasswordValidator;

use Illuminate\Validation\Validator;

class PasswordValidator extends Validator
{
    public $validationMessages = [
        'password_strength' => 'password so weak',
    ];

    public function __construct($translator, $data, $rules, $messages = [], $customAttributes = [])
    {
        parent::__construct($translator, $data, $rules, $messages, $customAttributes);
        $this->setCustomMessages($this->validationMessages);
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
        }

        switch ($strong) {
            case 1:
                return $this->strongOne($value);
            case 2:
                return $this->strongTwo($value);
            case 3:
                return $this->strongThree($value);
            case 4:
                return $this->strongFour($value);
            default:
                return $this->strongFive($value);
        }
    }

    private function strongOne($value): bool
    {
        return strlen($value) >= 6;
    }

    private function strongTwo($value): bool
    {
        return $this->strongOne($value) and preg_match("/[a-z]/", $value);
    }

    private function strongThree($value): bool
    {
        return $this->strongTwo($value) and preg_match("/[A-Z]/", $value);
    }

    private function strongFour($value): bool
    {
        return $this->strongThree($value) and preg_match("/[0-9]/", $value);
    }

    private function strongFive($value): bool
    {
        return $this->strongFour($value) and preg_match("/[@$!%*#?&]/", $value);
    }
}
