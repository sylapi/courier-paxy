<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Exception;
use Sylapi\Courier\Paxy\Session;
use Sylapi\Courier\Contracts\Shipment;
use GuzzleHttp\Exception\ClientException;
use Sylapi\Courier\Paxy\Entities\Booking;
use Sylapi\Courier\Exceptions\TransportException;
use Sylapi\Courier\Paxy\Helpers\ResponseErrorHelper;
use Sylapi\Courier\Paxy\Responses\Shipment as ShipmentResponse;
use Sylapi\Courier\Contracts\CourierCreateShipment as CourierCreateShipmentContract;
use Sylapi\Courier\Responses\Shipment as ResponseShipment;

class CourierCreateShipment implements CourierCreateShipmentContract
{
    private $session;

    const API_BOOKS = '/books';
    const API_PARCELS = '/parcels';

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function createShipment(Shipment $shipment): ResponseShipment
    {
        $response = new ShipmentResponse();

        $bookNr = null;
        $trackingNr = null;

        try {
            $bookNr = $this->createBook($this->getBook($shipment));
            
            $trackingNr = $this->createParcel($this->getParcel($bookNr, $shipment));

            $response->setResponse($response);
            $response->setReferenceId((string) $bookNr);
            $response->setShipmentId((string) $bookNr);
            $response->setTrackingId((string) $trackingNr);
            return $response;

        } catch (ClientException $e) {

            if($bookNr) {
                $this->closeBook($bookNr, $trackingNr);
            }
            throw new TransportException(ResponseErrorHelper::message($e));
        } catch (Exception $e) {
            throw new TransportException($e->getMessage(), $e->getCode());
            
        }
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

        /**
         * @var \Sylapi\Courier\Paxy\Entities\Options $options 
         */
        $options = $shipment->getOptions();

        $data = [
            'bookNr' => $bookNr,
            'carrierCode' => $options->getShippingType(),
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

        $services = $shipment->getServices();
        
        if($services) {
            foreach($services as $service) {
                $service->setRequest($data);
                $data = $service->handle();
            }
        }

        return $data;
    }

    private function closeBook(?string $bookNr, ?string $trackingNr):void
    {
        $postShipment = new CourierPostShipment($this->session);
        $booking = new Booking;
        $booking->setShipmentId($bookNr);
        $booking->setTrackingId($trackingNr);
        $postShipment->postShipment($booking);
    }
}
