<?php

namespace Up\Core\Domain\Application;

use Up\Core\Handlers\EnumHandler;

abstract class StatusEnum extends EnumHandler
{
    public const PENDING = 'pending';
    public const ACCEPTED = 'accepted';
    public const REJECTED = 'rejected';
}
