<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Rakit\Validation\Validator;
use Sylapi\Courier\Abstracts\Booking;

class PaxyBooking extends Booking
{
    private $trackingId;

    public function getTrackingId()
    {
        return $this->trackingId;
    }

    public function setTrackingId(string $trackingId): PaxyBooking
    {
        $this->trackingId = $trackingId;

        return $this;
    }

    public function validate(): bool
    {
        $rules = [
            'shipmentId'   => 'required',
            'trackingId'   => 'nullable',
        ];

        $data = [
            'shipmentId' => $this->getShipmentId(),
            'trackingId' => $this->getTrackingId(),
        ];

        $validator = new Validator();

        $validation = $validator->validate($data, $rules);
        if ($validation->fails()) {
            $this->setErrors($validation->errors()->toArray());

            return false;
        }

        return true;
    }
}
