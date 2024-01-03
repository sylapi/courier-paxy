<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Exception;
use GuzzleHttp\Exception\ClientException;
use Sylapi\Courier\Contracts\CourierGetStatuses as CourierGetStatusesContract;
use Sylapi\Courier\Contracts\Status as StatusContract;
use Sylapi\Courier\Entities\Status;
use Sylapi\Courier\Enums\StatusType;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Helpers\ResponseHelper;

class CourierGetStatuses implements CourierGetStatusesContract
{
    private $session;

    const API_PATH = '/trackings';

    public function __construct(PaxySession $session)
    {
        $this->session = $session;
    }

    public function getStatus(string $trackingId): StatusContract
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

            return new Status((string) new PaxyStatusTransformer((string) $status));
        } catch (ClientException $e) {
            $exception = new TransportException(PaxyResponseErrorHelper::message($e));
            $status = new Status(StatusType::APP_RESPONSE_ERROR);
            ResponseHelper::pushErrorsToResponse($status, [$exception]);

            return $status;
        } catch (Exception $e) {
            $exception = new TransportException($e->getMessage(), $e->getCode());
            $status = new Status(StatusType::APP_RESPONSE_ERROR);
            ResponseHelper::pushErrorsToResponse($status, [$exception]);

            return $status;
        }
    }



}
