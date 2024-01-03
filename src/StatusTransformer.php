<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Sylapi\Courier\Abstracts\StatusTransformer as StatusTransformerAbstract;
use Sylapi\Courier\Enums\StatusType;

class StatusTransformer extends StatusTransformerAbstract
{
    /**
     * @var array<string, string>
     */
    public $statuses = [
        'pending'   => StatusType::NEW->value,
        'scanned'   => StatusType::PROCESSING->value,
        'sent'   => StatusType::SENT->value,
        'transport'   => StatusType::SPEDITION_DELIVERY->value,
        'point'   => StatusType::WAREHOUSE_ENTRY->value,
        'delivery'   => StatusType::PROCESSING->value,
        'unclaimed'   => StatusType::PROCESSING_FAILED->value,
        'back'   => StatusType::RETURNED->value,
        'notAccepted'   => StatusType::PROCESSING_FAILED->value,
        'delivered'   => StatusType::DELIVERED->value,
    ];
}
