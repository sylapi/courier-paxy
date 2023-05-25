<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Exception;
use Sylapi\Courier\Paxy\PaxyBooking;
use Sylapi\Courier\Contracts\Booking;
use Sylapi\Courier\Entities\Response;
use GuzzleHttp\Exception\ClientException;
use Sylapi\Courier\Helpers\ResponseHelper;
use Sylapi\Courier\Contracts\CourierPostShipment;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Contracts\Response as ResponseContract;

class PaxyCourierPostShipment implements CourierPostShipment
{
    const API_PATH = '/books/:book/close';

    private $session;

    public function __construct(PaxySession $session)
    {
        $this->session = $session;
    }

    public function postShipment(Booking $booking): ResponseContract
    {
        $response = new Response();

        try {
            $stream = $this->session
                ->client()
                ->post(
                    $this->getPath($booking->getShipmentId()),
                    []
                );

            $result = json_decode($stream->getBody()->getContents());

            if ($result === null && json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Json data is incorrect');
            }

            $response->shipmentId = $booking->getShipmentId();
            /**
             * @var PaxyBooking $booking
             */
            $response->trackingId = $booking->getTrackingId();
        } catch (ClientException $e) {
            $exception = new TransportException(PaxyResponseErrorHelper::message($e));
            ResponseHelper::pushErrorsToResponse($response, [$exception]);

            return $response;
        } catch (Exception $e) {
            $exception = new TransportException($e->getMessage(), $e->getCode());
            ResponseHelper::pushErrorsToResponse($response, [$exception]);
        }

        return $response;
    }

    private function getPath(string $value)
    {
        return str_replace(':book', $value, self::API_PATH);
    }
}
