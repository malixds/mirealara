<?php

namespace App\Dto\User;

class ExecutorsSearchUserDto
{
    public function __construct(
        readonly public array $subjectsFromRequest,
        readonly public array $subjects
    )
    {
    }
}
