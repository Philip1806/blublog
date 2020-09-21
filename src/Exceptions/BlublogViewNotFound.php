<?php

namespace Blublog\Blublog\Exceptions;

use Exception;

class BlublogViewNotFound extends Exception
{
    public function report()
    {
    }

    public function render($request)
    {
        return view("blublog::error")->with('error', "BlublogViewNotFound")->with('msg', __('blublog.BlublogViewNotFound'));
    }
}
