<?php

namespace App\Http\Requests;

use App\Enums\TodoStatusEnum;
use App\Foundation\Rules\EnumKeys;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTodoRequest extends FormRequest
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
        return [
            'due_on' => ['sometimes', 'date_format:Y-m-d H:i:s'],
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'title' => ['sometimes', 'string', 'min:1', 'max:256'],
            'status' => ['sometimes', 'string', new EnumKeys(TodoStatusEnum::class)]
        ];
    }
}
