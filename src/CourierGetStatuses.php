<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Exception;
use Sylapi\Courier\Paxy\Responses\Status as StatusResponse;
use Sylapi\Courier\Enums\StatusType;
use GuzzleHttp\Exception\ClientException;
use Sylapi\Courier\Paxy\StatusTransformer;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Paxy\Helpers\ResponseErrorHelper;
use Sylapi\Courier\Contracts\CourierGetStatuses as CourierGetStatusesContract;
use Sylapi\Courier\Responses\Status as ResponseStatus;

class CourierGetStatuses implements CourierGetStatusesContract
{
    private $session;

    const API_PATH = '/trackings';

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getStatus(string $trackingId): ResponseStatus
    {
        try {
            $stream = $this->session
                ->client()
                ->request(
                    'POST',
                    self::API_PATH,
                    ['json' => [
                        'trackingNrs' => [
                            $trackingId
                        ],
                    ]]
                );


            $result = json_decode($stream->getBody()->getContents());

            if ($result === null && json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Json data response is incorrect');
            }
        
            $status = $result->items[0]->statusCode ?? StatusType::APP_RESPONSE_ERROR;

            return new StatusResponse((string) new StatusTransformer((string) $status));
        } catch (ClientException $e) {
            throw new TransportException(ResponseErrorHelper::message($e));
        } catch (Exception $e) {
            throw  new TransportException($e->getMessage(), $e->getCode());
        }
    }



}
