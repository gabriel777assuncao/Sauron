<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\RedirectResponse;

class QuestionController extends Controller
{
    public function store(): RedirectResponse
    {
        Question::query()->create(
            request()->validate([
                'question' => 'required|max:255|min:10|ends_with:?',
            ],
            ));

        return to_route('dashboard');
    }
}
