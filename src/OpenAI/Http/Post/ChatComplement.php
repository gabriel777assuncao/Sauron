<?php

namespace src\OpenAI\Http\Post;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class ChatComplement extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    private string $question;

    private array $existingQuestions;

    public function resolveEndpoint(): string
    {
        return '/chat/completions';
    }

    public function setQuestionParameters(string $question, array $existingQuestions): self
    {
        $this->question = $question;
        $this->existingQuestions = $existingQuestions;

        return $this;
    }

    protected function defaultBody(): array
    {
        return [
            'model' => 'gpt-4o',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an assistant that checks if a question already exists in a database.',
                ],
                [
                    'role' => 'user',
                    'content' => "The question the user wants to register is: \"$this->question\".
                    The existing questions are: ".implode('; ', $this->existingQuestions).".
                    Does the question already exist or is it very similar? Answer with the title of the question that already exists,
                    if the question doesn't have similarity with any of existing questions, answer with 'none'",
                ],
            ],
            'temperature' => 0.3,
        ];
    }
}
