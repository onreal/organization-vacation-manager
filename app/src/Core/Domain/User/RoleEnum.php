<?php

namespace Up\Core\Domain\User;

use Up\Core\Handlers\EnumHandler;

abstract class RoleEnum extends EnumHandler
{
    public const ADMIN = 'admin';
    public const EMPLOYEE = 'employee';
}
