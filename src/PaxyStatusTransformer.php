<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Sylapi\Courier\Abstracts\StatusTransformer;
use Sylapi\Courier\Enums\StatusType;

class PaxyStatusTransformer extends StatusTransformer
{
    /**
     * @var array<string, string>
     */
    public $statuses = [
        'pending'   => StatusType::NEW,
        'scanned'   => StatusType::PROCESSING,
        'sent'   => StatusType::SENT,
        'transport'   => StatusType::SPEDITION_DELIVERY,
        'point'   => StatusType::WAREHOUSE_ENTRY,
        'delivery'   => StatusType::PROCESSING,
        'unclaimed'   => StatusType::PROCESSING_FAILED,
        'back'   => StatusType::RETURNED,
        'notAccepted'   => StatusType::PROCESSING_FAILED,
        'delivered'   => StatusType::DELIVERED,
    ];
}
