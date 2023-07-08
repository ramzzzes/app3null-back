<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PasswordRule implements Rule
{
    public function passes($attribute, $value)
    {
        return preg_match('/^(?=.*[a-z].*[a-z])(?=.*[A-Z].*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/', $value);
    }

    public function message()
    {
        return 'The :attribute must have at least 8 characters, 2 uppercase letters, 2 lowercase letters, 1 number, and 1 special symbol.';
    }
}
