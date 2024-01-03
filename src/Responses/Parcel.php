<?php

namespace Sylapi\Courier\Paxy\Responses;

use Sylapi\Courier\Responses\Parcel as ParcelResponse;
use Sylapi\Courier\Contracts\Response as ResponseContract;

class Parcel extends ParcelResponse
{
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
}
