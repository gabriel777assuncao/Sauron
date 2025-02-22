<?php

namespace Tests\Feature\Integrations\OpenAI;

use App\Models\Question;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\DataProvider;
use Saloon\Config;
use Saloon\Exceptions\Request\Statuses\PaymentRequiredException;
use Saloon\Exceptions\Request\Statuses\TooManyRequestsException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use src\OpenAI\Http\OpenAIConnector;
use src\OpenAI\Http\Post\ChatComplement;
use src\OpenAI\Manager\ChatComplementsManager;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ChatComplements extends TestCase
{
    use LazilyRefreshDatabase;

    private Question $question;

    public function setUp(): void
    {
        parent::setUp();

        Config::preventStrayRequests();
        MockClient::destroyGlobal();

        Log::spy();
    }

    #[DataProvider('provideExceptions')]
    public function test_if_it_will_catch_an_exception_on_the_response_ocurred(int $statusCode): void
    {
        MockClient::global([
            ChatComplement::class => MockResponse::make(status: $statusCode),
        ]);

        Log::spy();

        $manager = new ChatComplementsManager(new OpenAIConnector(), new ChatComplement());
        $manager->sendChatComplements('This is a very good question?', []);

        Log::shouldHaveReceived('error')->once();
    }

    public function test_if_it_will_throw_an_exception_an_unmapped_exception(): void
    {
        MockClient::global([
            ChatComplement::class => MockResponse::make(status:Response::HTTP_PAYMENT_REQUIRED),
        ]);

        Log::spy();

        $manager = new ChatComplementsManager(new OpenAIConnector(), new ChatComplement());

        $this->assertThrows(
            fn() => $manager->sendChatComplements('This is a very good question?', []),
            PaymentRequiredException::class
        );
    }

    public static function provideExceptions(): iterable
    {
        yield [Response::HTTP_TOO_MANY_REQUESTS];
        yield [Response::HTTP_FORBIDDEN];
    }
}
