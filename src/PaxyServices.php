<?php

declare(strict_types=1);

namespace Sylapi\Courier\Paxy;

use Sylapi\Courier\Abstracts\Enum;

class PaxyServices extends Enum
{
    const COURIER_STANDARD = 'Paxy_courier_standard'; //Przesyłka kurierska standardowa
    const COURIER_EXPRESS_1000 = 'Paxy_courier_express_1000'; //Przesyłka kurierska z doręczeniem do 10:00
    const COURIER_EXPRESS_1200 = 'Paxy_courier_express_1200'; //Przesyłka kurierska z doręczeniem do 12:00
    const COURIER_EXPRESS_1700 = 'Paxy_courier_express_1700'; //Przesyłka kurierska z doręczeniem do 12:00
    const COURIER_PALETTE = 'Paxy_courier_palette'; //Przesyłka kurierska Paleta Standard
    const COURIER_LOCAL_STANDARD = 'Paxy_courier_local_standard'; //Przesyłka kurierska Lokalna Standardowa
    const COURIER_LOCAL_EXPRESS = 'Paxy_courier_local_express'; //Przesyłka kurierska Lokalna Expresowa
    const COURIER_LOCAL_SUPER_EXPRESS = 'Paxy_courier_local_super_express'; //Przesyłka kurierska Lokalna Super Expresowa
    const LOCKER_STANDARD = 'Paxy_locker_standard'; //Przesyłka paczkomatowa - standardowa
}
