<?php

namespace App\Enums;

enum PostStatusEnum: string
{
    case ACTIVE = 'active';
    case ACCEPTED = 'accepted';
    case CONFIRMED = 'confirmed';
    case REJECTED = 'rejected';

}
