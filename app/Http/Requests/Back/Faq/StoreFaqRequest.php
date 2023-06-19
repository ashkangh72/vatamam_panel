<?php

namespace App\Http\Requests\Back\Faq;

use Illuminate\Foundation\Http\FormRequest;

class StoreFaqRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'question' => 'required|string',
            'answer' => 'required|string',
            'ordering' => 'nullable|numeric'
        ];
    }
}
