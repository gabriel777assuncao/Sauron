<?php

namespace App\Http\Requests\Questions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'question' => [
                'required',
                'min:10',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (! Str::endsWith($value, '?')) {
                        $fail(__('messages.custom.question.invalid-content'));
                    }
                },
            ],
        ];
    }
}
