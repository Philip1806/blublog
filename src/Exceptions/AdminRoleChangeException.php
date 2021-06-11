<?php

namespace Blublog\Blublog\Exceptions;

use Blublog\Blublog\Models\Log;
use Exception;

class AdminRoleChangeException extends Exception
{
    public function __construct()
    {
        parent::__construct('You can not change permissions for the role Administrators.');
    }
    public function report()
    {
        Log::add('AdminRoleChangeException', 'error', 'Blocked try to edit admin role permissions.');
    }
}
