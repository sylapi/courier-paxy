<?php

namespace Sylapi\Courier\Paxy\Responses;
use Sylapi\Courier\Paxy\Entities\Booking;
use Sylapi\Courier\Responses\Shipment as ShipmentResponse;
use Sylapi\Courier\Contracts\Response as ResponseContract;

class Shipment extends ShipmentResponse
{
    private string $referenceId;
    private string $trackingId;

    public function setTrackingId(string $trackingId): ResponseContract
    {
        $this->trackingId = $trackingId;

        return $this;
    }

    public function getTrackingId(): ?string
    {
        return $this->trackingId;
    }

    public function setReferenceId(string $referenceId): ResponseContract
    {
        $this->referenceId = $referenceId;

        return $this;
    }

    public function getReferenceId(): ?string
    {
        return $this->referenceId;
    }

    public function getBooking() : ?Booking
    {

        if(!$this->getResponse()) {
            return null;
        }

        $booking = new Booking();
        $booking->setShipmentId($this->getShipmentId());
        $booking->setTrackingId($this->getShipmentId());

        return $booking;

    }
}
