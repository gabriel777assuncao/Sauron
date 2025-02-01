<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Closure;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class QuestionController extends Controller
{
    use AuthorizesRequests;

    public function __invoke(): View
    {
        return view('dashboard', [
            'questions' => Question::query()
                ->withCount([
                    'votes as count_likes' => function ($query) {
                        $query->where('likes', '>', 0);
                    },
                    'votes as count_unlikes' => function ($query) {
                        $query->where('unlikes', '>', 0);
                    },
                ])
                ->latest()
                ->get(),
        ]);
    }

    public function index(): View
    {
        return view(('questions.index'), [
            'questions' => auth()->user()->questions,
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

        Question::query()->create([...$attributes, 'draft' => true, 'created_by' => auth()->id()]);

        return back();
    }

    public function publish(Question $question): RedirectResponse
    {
        $this->authorize('publish', $question);

        $question->update(['draft' => false]);

        return to_route('dashboard');
    }

    public function destroy(Question $question): RedirectResponse
    {
        $this->authorize('destroy', $question);

        $question->delete();

        return back();
    }
}
