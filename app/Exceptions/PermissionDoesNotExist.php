<?php

namespace App\Exceptions;

use Exception;
use InvalidArgumentException;

class PermissionDoesNotExist extends InvalidArgumentException
{
    public static function named(string $permissionName)
    {
        return new static("There is no permission named `{$permissionName}`.");
    }

    public static function withId(int $permissionId)
    {
        return new static("There is no permission with id `{$permissionId}`.");
    }
}
