<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class QuestionController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'questions' => Question::query()->latest()->get(),
        ]);
    }

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

        Question::query()->create([
            'question' => $attributes['question'],
        ]);

        return to_route('dashboard');
    }
}
