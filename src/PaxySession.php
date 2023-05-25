<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use GuzzleHttp\Client;

class PaxySession
{
    private $parameters;
    private $client;
    private $token;
    private $key;

    public function __construct(PaxyParameters $parameters)
    {
        $this->parameters = $parameters;
        $this->client = null;
        $this->token = $this->parameters->token ?? null;
        $this->key = $this->parameters->key ?? null;
    }

    public function parameters(): PaxyParameters
    {
        return $this->parameters;
    }

    public function client(): Client
    {
        if (!$this->client) {
            $this->initializeSession();
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

    private function initializeSession(): void
    {
        $this->client = new Client([
            'base_uri' => $this->parameters->apiUrl,
            'headers'  => [
                'Content-Type'  => 'application/json',
                'CL-API-KEY' => $this->key(),
                'CL-API-TOKEN' => $this->token(),
            ],
        ]);
    }
}
