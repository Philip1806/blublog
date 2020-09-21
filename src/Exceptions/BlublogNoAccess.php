<?php

namespace Blublog\Blublog\Exceptions;

use Exception;

class BlublogNoAccess extends Exception
{
    public function report()
    {
    }

    public function render($request)
    {
        return view("blublog::error")->with('error', "BlublogNoAccess")->with('msg', __('blublog.BlublogNoAccess'));
    }
}
