<?php

namespace App\Enums;

enum UserMonitoringState: string
{
    case ACTIVE = 'active';
    case WARNING = 'warning';
    case TRIGGERED = 'triggered';
    case PURGED = 'purged';
}
