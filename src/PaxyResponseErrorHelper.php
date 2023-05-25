<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use GuzzleHttp\Exception\ClientException;

class PaxyResponseErrorHelper
{
    const DEFAULT_MESSAGE = 'Something went wrong!';

    public static function message(ClientException $e): string
    {
        $message = null;
        $content = json_decode($e->getResponse()->getBody()->getContents());

        if (isset($content->message)) {
            $message = $content->message;
        }

        $message = $message ?? self::DEFAULT_MESSAGE;

        return $message;
    }
}
