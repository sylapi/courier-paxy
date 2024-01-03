<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use GuzzleHttp\Client;
use Sylapi\Courier\Paxy\Entities\Credentials;

class Session
{
    private $credentials;
    private $client;
    private $token;
    private $key;

    public function __construct(Credentials $credentials)
    {
        $this->credentials = $credentials;
        $this->client = null;
        $this->token = $this->credentials->getPassword();
        $this->key = $this->credentials->getLogin();
    }

    public function client(): Client
    {
        if (!$this->client) {
            $this->client = $this->initializeSession();
        }

        return $this->client;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function key(): string
    {
        return $this->key;
    }

    private function initializeSession(): Client
    {
        $this->client = new Client([
            'base_uri' => $this->credentials->getApiUrl(),
            'headers'  => [
                'Content-Type'  => 'application/json',
                'CL-API-KEY' => $this->key(),
                'CL-API-TOKEN' => $this->token(),
            ],
        ]);

        return $this->client;
    }
}
