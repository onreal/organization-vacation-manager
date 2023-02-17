<?php

namespace Up\Core\Enum;

abstract class ApplicationStatus extends EnumBase
{
    public const PENDING = 'pending';
    public const ACCEPTED = 'accepted';
    public const REJECTED = 'rejected';
}
