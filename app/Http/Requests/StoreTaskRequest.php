<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'txt' => 'required|max:191'
        ];
    }

    public function messages(): array
    {
        return [
            'txt.required' => 'Task field can not be empty',
            'txt.max' => 'Text for new task exceeded allowance of 191 characters',
        ];
    }
}
