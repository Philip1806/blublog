<?php

namespace Blublog\Blublog\Exceptions;

use Exception;

class BlublogNoFileDriver extends Exception
{
    public function report()
    {
    }

    public function render($request)
    {
        return view("blublog::error")->with('error', "BlublogNoFileDriver")->with('msg', __('blublog.BlublogNoFileDriver'));
    }
}
