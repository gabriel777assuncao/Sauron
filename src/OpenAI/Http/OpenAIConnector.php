<?php

namespace src\OpenAI\Http;

use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Saloon\Traits\Plugins\HasTimeout;
use Saloon\Traits\RequestProperties\HasTries;

class OpenAIConnector extends Connector
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;
    use HasTimeout;
    use HasTries;

    protected int $connectTimeout = 60;

    protected int $requestTimeout = 120;

    public function __construct()
    {
        $this->retryInterval = app()->environment('production') ? 2000 : null;
    }

    public function resolveBaseUrl(): string
    {
        return config('platforms.openai.url');
    }

    public function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator(config('platforms.openai.api_key'));
    }
}
