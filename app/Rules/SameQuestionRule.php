<?php

namespace App\Rules;

use App\Models\Question;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SameQuestionRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->validationRule($value)) {
            $fail(__('custom.question.already_exists'));
        }
    }

    private function validationRule(string $question): bool
    {
        return Question::whereQuestion($question)->exists();
    }
}
