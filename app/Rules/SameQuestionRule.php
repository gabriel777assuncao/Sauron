<?php

namespace App\Rules;

use App\Models\Question;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use src\OpenAI\Http\OpenAIConnector;
use src\OpenAI\Http\Post\ChatComplement;
use src\OpenAI\Manager\ChatComplementsManager;

class SameQuestionRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $existingQuestions = Question::pluck('question')->toArray();

        $similarQuestion = (new ChatComplementsManager(new OpenAIConnector, new ChatComplement))->sendChatComplements($value, $existingQuestions);

        if ($similarQuestion !== null) {
            $fail(__('messages.custom.question.already_exists')."\"$similarQuestion\"");
        }
    }
}
