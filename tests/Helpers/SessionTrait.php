<?php

namespace Sylapi\Courier\Paxy\Tests\Helpers;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Sylapi\Courier\Paxy\Session;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Client as HttpClient;
use Sylapi\Courier\Paxy\PaxySession;
use Sylapi\Courier\Paxy\PaxyParameters;

trait SessionTrait
{
    private function getSessionMock(array $responses)
    {
        $responseMocks = [];

        foreach ($responses as $response) {
            $responseMocks[] = new Response((int) $response['code'], $response['header'], $response['body']);
        }

        $mock = new MockHandler($responseMocks);

        $handlerStack = HandlerStack::create($mock);
        $client = new HttpClient(['handler' => $handlerStack]);

        $sessionMock = $this->createMock(Session::class);
        $sessionMock->method('client')
            ->willReturn($client);

        return $sessionMock;
    }

}
