<?php

namespace Blublog\Blublog\Exceptions;

use Blublog\Blublog\Models\Log;
use InvalidArgumentException;
use Throwable;

class InvalidPostStatusException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Post status is not found or invalid.');
    }
    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        Log::add('InvalidPostStatusException', 'error', 'Post status is not found or invalid.');
    }
}
