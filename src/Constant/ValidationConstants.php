<?php

namespace App\Constant;

final class ValidationConstants
{
    public const NOT_AVAILABLE = 'N/A';
    public const TBD = 'TBD';
    public const SOURCE_SYSTEM_CODE_DEFAULT = 'CUSTOMER_CLOUD';
    public const STOCK_STATUS_CODE_DEFAULT = 'ACTIVE';
    public const SOLD_FLAG_DEFAULT = 0;

    public const LK_DEFAULT_VALUE_ARRAY = [self::NOT_AVAILABLE, self::TBD];

    public const VEHICLE_TYPE_CAR = 'CAR';
    public const VEHICLE_TYPE_YACHT = 'YACHT';
}
