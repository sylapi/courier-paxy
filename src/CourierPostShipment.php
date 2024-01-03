<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Exception;
use Sylapi\Courier\Paxy\Entities\Booking as PaxyBooking;
use Sylapi\Courier\Contracts\Booking;
use GuzzleHttp\Exception\ClientException;
use Sylapi\Courier\Helpers\ResponseHelper;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Paxy\Helpers\ResponseErrorHelper;
use Sylapi\Courier\Contracts\Response as ResponseContract;
use Sylapi\Courier\Paxy\Responses\Parcel as ParcelResponse;
use Sylapi\Courier\Contracts\CourierPostShipment as CourierPostShipmentContract;

class CourierPostShipment implements CourierPostShipmentContract
{
    const API_PATH = '/books/:book/close';

    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function postShipment(Booking $booking): ResponseContract
    {
        $response = new ParcelResponse();

        try {
            $stream = $this->session
                ->client()
                ->post(
                    $this->getPath((string) $booking->getShipmentId()),
                    []
                );

            $result = json_decode($stream->getBody()->getContents());

            if ($result === null && json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Json data is incorrect');
            }

            $response->setShipmentId($booking->getShipmentId());
            /**
             * @var PaxyBooking $booking
             */
            $response->setTrackingId($booking->getTrackingId());

            return $response;

        } catch (ClientException $e) {
            throw new TransportException(ResponseErrorHelper::message($e));
        } catch (Exception $e) {
            throw new TransportException($e->getMessage(), $e->getCode());
        }

        
    }

    private function getPath(string $value)
    {
        return str_replace(':book', $value, self::API_PATH);
    }
}
