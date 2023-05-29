<?php

namespace Sylapi\Courier\Paxy\Tests\Unit;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Paxy\PaxyParameters;
use Sylapi\Courier\Paxy\PaxySession;

class PaxySessionTest extends PHPUnitTestCase
{
    public function testPaxySessionParameters()
    {
        $paxySession = new PaxySession(PaxyParameters::create([
            'apiUrl'    => 'https://test.paxy.api',
            'token'     => 'asdef12345',
            'key'       => '98765qwerty',
        ]));
        $this->assertInstanceOf(PaxyParameters::class, $paxySession->parameters());
        $this->assertInstanceOf(Client::class, $paxySession->client());
    }
}
