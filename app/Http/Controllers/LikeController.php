<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\RedirectResponse;

class LikeController extends Controller
{
    public function like(Question $question): RedirectResponse
    {
        auth()->user()->like($question);

        return back();
    }

    public function unlike(Question $question): RedirectResponse
    {
        auth()->user()->unlike($question);

        return back();
    }
}
