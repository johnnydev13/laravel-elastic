<?php

namespace App\Http\Requests;

use App\Enums\TodoStatusEnum;
use App\Foundation\Rules\EnumKeys;
use Illuminate\Validation\Rule;

class GetTodosRequest extends PaginationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return parent::rules() + [
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'status' => ['sometimes', 'string', new EnumKeys(TodoStatusEnum::class)],
            'date_from' => ['sometimes', 'date'],
            'date_to' => [Rule::when($this->date_from, 'required'), 'date']
        ];
    }
}
