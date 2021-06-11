<?php

namespace Blublog\Blublog\Exceptions;

use Blublog\Blublog\Models\Log;
use Exception;

class PostStatusPermission extends Exception
{
    public function __construct()
    {
        parent::__construct('No permissions for post status change.');
    }
    public function report()
    {
        Log::add('PostStatusPermission', 'error', 'No permissions for post status change.');
    }
}
