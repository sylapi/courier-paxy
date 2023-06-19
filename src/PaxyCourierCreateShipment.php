<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Exception;
use GuzzleHttp\Exception\ClientException;
use Sylapi\Courier\Contracts\CourierCreateShipment;
use Sylapi\Courier\Contracts\CourierPostShipment;
use Sylapi\Courier\Contracts\Response as ResponseContract;
use Sylapi\Courier\Contracts\Shipment;
use Sylapi\Courier\Entities\Response;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Helpers\ResponseHelper;

class PaxyCourierCreateShipment implements CourierCreateShipment
{
    private $session;

    const API_BOOKS = '/books';
    const API_PARCELS = '/parcels';

    public function __construct(PaxySession $session)
    {
        $this->session = $session;
    }

    public function createShipment(Shipment $shipment): ResponseContract
    {
        $response = new Response();

        $bookNr = null;
        $trackingNr = null;

        try {
            $bookNr = $this->createBook($this->getBook($shipment));
            $response->referenceId = $bookNr;
            $trackingNr = $this->createParcel($this->getParcel($bookNr, $shipment));
            $response->referenceId = $bookNr;

            $response->shipmentId = $bookNr;
            $response->trackingId = $trackingNr;
        } catch (ClientException $e) {
            $exception = new TransportException(PaxyResponseErrorHelper::message($e));
            ResponseHelper::pushErrorsToResponse($response, [$exception]);
            if($bookNr) {
                $this->closeBook($bookNr);
            }
            return $response;
        } catch (Exception $e) {
            $exception = new TransportException($e->getMessage(), $e->getCode());
            ResponseHelper::pushErrorsToResponse($response, [$exception]);
        }

        return $response;
    }

    private function createBook(array $request) : string
    {
        $stream = $this->session
            ->client()
            ->request(
                'POST',
                self::API_BOOKS,
                ['json' => $request]
            );

        $result = json_decode($stream->getBody()->getContents());

        if ($result === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Json data response is incorrect');
        }

        return $result->book->nr;
    }

    private function getBook(Shipment $shipment): array
    {
        return [
            'countryCode' => $shipment->getReceiver()->getCountryCode(),
            'comments' => $shipment->getContent(),
            'postingDate' => ''
        ];
    }

    private function createParcel(array $request) : string
    {
        $stream = $this->session
            ->client()
            ->request(
                'POST',
                self::API_PARCELS,
                ['json' => $request]
            );

        $result = json_decode($stream->getBody()->getContents());

        if ($result === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Json data response is incorrect');
        }

        return $result->trackingNr;
    }    

    private function getParcel(string $bookNr, Shipment $shipment): array
    {
        $data = [
            'bookNr' => $bookNr,
            'carrierCode' => $this->session->parameters()->speditionCode,
            'type' => 'parcel',
            'quantity' => $shipment->getQuantity(),
            'recipientName' => $shipment->getReceiver()->getFullName(),
            'recipientCity' => $shipment->getReceiver()->getCity(),
            'recipientRegion' => '',
            'recipientPostCode' => $shipment->getReceiver()->getZipCode(),
            'recipientStreet' => $shipment->getReceiver()->getStreet(),
            'recipientAddressNr' => $shipment->getReceiver()->getHouseNumber().' '.$shipment->getReceiver()->getApartmentNumber(),
            'recipientEmail' =>  $shipment->getReceiver()->getEmail(),
            'recipientTel' => $shipment->getReceiver()->getPhone(),
            'weight' => $shipment->getParcel()->getWeight(),
            'cod' => '0',
            'insurance' => '0',
            'reference' => $shipment->getContent(),
            'docWz' => '',
            'pointId' => '',
            'officeId' => '',
            'externalNr' => ''
        ];

        if ($this->session->parameters()->hasProperty('cod') && is_array($this->session->parameters()->cod)) {
            $data['cod'] = (int) $this->session->parameters()->cod;
        }

        if ($this->session->parameters()->hasProperty('insurance') && is_array($this->session->parameters()->insurance)) {
            $data['insurance'] = (int)  $this->session->parameters()->insurance;
        }

        return $data;
    }

    private function closeBook($bookNr)
    {
        $postShipment = new PaxyCourierPostShipment($this->session);
        $booking = new PaxyBooking;
        $booking->setShipmentId($bookNr);
        $postShipment->postShipment($booking);
    }
}
