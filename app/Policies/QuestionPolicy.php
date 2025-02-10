<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\User;

class QuestionPolicy
{
    public function archive(User $user, Question $question): bool
    {
        return $question->createdBy->is($user);
    }

    public function publish(User $user, Question $question): bool
    {
        return $question->createdBy->is($user);
    }

    public function destroy(User $user, Question $question): bool
    {
        return $question->createdBy->is($user);
    }

    public function edit(User $user, Question $question): bool
    {
        return $question->createdBy->is($user) && $question->draft;
    }

    public function update(User $user, Question $question): bool
    {
        return $question->createdBy->is($user);
    }
}
