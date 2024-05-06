<?php

namespace App\Enums;

enum UserRolesEnum:string
{
    case ADMIN = 'Admin';
    case ADMINSLUG = 'admin';
    case REGULAR = 'Regular';
    case REGULARSLUG = 'regular';
    case WORKER = 'Worker';
    case WORKERSLUG = 'worker';
}
