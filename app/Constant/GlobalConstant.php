<?php

namespace App\Constant;

class GlobalConstant
{
    public const TYPE_SCAN = 0;
    public const TYPE_FOLLOW = 1;
    public const TYPE_RUNNING = 2;

    public const ROLE_CUSTOMER = 2;
    public const ROLE_USER = 0;
    public const ROLE_ADMIN = 1;

    public const IS_OFF = 0;
    public const IS_ON = 1;
    public const IS_RESET = 2;

    public const STATUS_OK = 0;
    public const STATUS_ERROR = 1;

    public const UTC_HOUR = 7;

    public const LINK_STATUS = [
        '0', '1', '2'
    ];
}
