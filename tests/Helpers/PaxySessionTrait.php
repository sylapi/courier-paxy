<?php

namespace Sylapi\Courier\Paxy\Tests\Helpers;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Client as HttpClient;
use Sylapi\Courier\Paxy\PaxySession;
use Sylapi\Courier\Paxy\PaxyParameters;

trait PaxySessionTrait
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

        $parametersMock = $this->createMock(PaxyParameters::class);
        $parametersMock->key = '1234567890';
        $parametersMock->token = '01b307acba4f54f55aafc33bb06bbbf6ca803e9a';
        $parametersMock->speditionCode = 'econtgr';

        $sessionMock = $this->createMock(PaxySession::class);
        $sessionMock->method('client')
            ->willReturn($client);
        $sessionMock->method('parameters')
            ->willReturn($parametersMock);

        return $sessionMock;
    }

}
