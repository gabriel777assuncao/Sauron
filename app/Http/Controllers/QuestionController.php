<?php

namespace App\Http\Controllers;

use App\Http\Requests\Questions\StoreRequest;
use App\Http\Requests\Questions\UpdateRequest;
use App\Models\Question;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
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
                ->orderByDesc('count_likes')
                ->orderByDesc('count_unlikes')
                ->paginate(10),
        ]);
    }

    public function index(): View
    {
        return view(('questions.index'), [
            'questions' => auth()->user()->questions,
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        auth()->user();
        $attributes = $request->validated();

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

    public function edit(Question $question): View
    {
        $this->authorize('edit', $question);

        return view('questions.edit', compact('question'));
    }

    public function update(Question $question, UpdateRequest $request): RedirectResponse
    {
        $this->authorize('edit', $question);

        $attributes = $request->validated();

        $question->update($attributes);

        return to_route('questions.index');
    }
}
