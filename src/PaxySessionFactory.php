<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

class PaxySessionFactory
{
    private $sessions = [];
    private $parameters;

    const API_URL = 'https://api.paxy.pl';

    public function session(PaxyParameters $parameters): PaxySession
    {
        $this->parameters = $parameters;
        $this->parameters->apiUrl = self::API_URL;

        $key = sha1($this->parameters->apiUrl.':'.$this->parameters->token);

        return (isset($this->sessions[$key])) ? $this->sessions[$key] : ($this->sessions[$key] = new PaxySession($this->parameters));
    }
}
