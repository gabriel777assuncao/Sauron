<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    public function store(): RedirectResponse
    {
        $attributes = request()->validate([
            'question' => [
                'required',
                'min:10',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (! Str::endsWith($value, '?')) {
                        $fail(__('messages.custom.question.invalid-content'));
                    }
                },
            ],
        ]);

        Question::query()->create($attributes);

        return to_route('dashboard');
    }
}
