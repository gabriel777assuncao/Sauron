<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\User;

class QuestionPolicy
{
    public function publish(User $user, Question $question): bool
    {
        return $question->createdBy->is($user);
    }
}
