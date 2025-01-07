<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuestionController extends Controller
{
    public function store(): RedirectResponse
    {
        Question::query()->create(
                request()->validate([
                    'question' => 'required|max:255',
                ],
            ));

        return to_route('dashboard');
    }
}
