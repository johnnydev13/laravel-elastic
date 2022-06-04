<?php

namespace App\Foundation\Rules;

use Illuminate\Validation\Rules\Enum;

class EnumKeys extends Enum
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
        if (is_null($value) || !function_exists('enum_exists') || !enum_exists($this->type)) {
            return false;
        }

        $options = array_map(fn ($item) => $item->name, $this->type::cases());

        return in_array($value, $options);
    }
}
