<?php

namespace src\OpenAI\Manager;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\Statuses\TooManyRequestsException;
use src\OpenAI\Http\OpenAIConnector;
use src\OpenAI\Http\Post\ChatComplement;
use Throwable;

class ChatComplementsManager
{
    public function __construct(
        protected readonly OpenAIConnector $connector,
        protected readonly ChatComplement $chatComplement,
    ) {}

    public function sendChatComplements(string $question, array $existingQuestions): ?string
    {
        try {
            $this->chatComplement->setQuestionParameters($question, $existingQuestions);
            $response = $this->connector->send($this->chatComplement)->json('choices.0.message.content');

            if (Str::contains('None', $response, true)) {
                return null;
            }

            return $response;
        } catch (TooManyRequestsException $exception){
            Log::error(
                'Error when trying to send a chat complement: too many requests.',
                [
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getCode(),
                    'question' => $question,
                ],
            );
        } catch (FatalRequestException $exception){
            Log::error(
                'Error when trying to send a chat complement: an fatal error occurred.',
                [
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getCode(),
                    'question' => $question,
                ],
            );
        } catch (Throwable $exception) {
            Log::error(
                'Error when trying to send a chat complement: unkown error.',
                [
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getCode(),
                    'question' => $question,
                ],
            );

            throw $exception;
        }
    }
}
