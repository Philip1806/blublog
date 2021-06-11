<?php

namespace Blublog\Blublog\Exceptions;

use Blublog\Blublog\Models\Log;
use InvalidArgumentException;
use Throwable;

class InvalidCategoryParentException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Category can not be parent to itself.');
    }
    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        Log::add('InvalidPostStatusException', 'error', 'Category can not be parent to itself.');
    }
}
