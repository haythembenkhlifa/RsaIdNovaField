<?php

namespace Haythem\RsaIdNovaField\rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class RsaIdValidator implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $isValid = filter_var(Arr::get(request(),"is_rsa_id_valid",false), FILTER_VALIDATE_BOOLEAN);
        return $isValid;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid ID Number.';
    }
}
