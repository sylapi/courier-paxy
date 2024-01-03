<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Sylapi\Courier\Paxy\Entities\Credentials;

class SessionFactory
{
    private $sessions = [];

    const API_URL = 'https://api.paxy.pl';

    public function session(Credentials $credentials): Session
    {
        $credentials->setApiUrl(self::API_URL);
        $apiUrl = self::API_URL;

        $key = sha1( $apiUrl.':'.$credentials->getLogin().':'.$credentials->getPassword());

        return (isset($this->sessions[$key])) ? $this->sessions[$key] : ($this->sessions[$key] = new Session($credentials));
    }
}
