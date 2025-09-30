<?php

namespace App\Enums;

enum Role: string
{
    case TENANT = 'tenant';
    case MEMBER = 'member';
}
