<?php

namespace Blublog\Blublog\Exceptions;

use Blublog\Blublog\Models\Log;
use Exception;

class BlublogModulDisabled extends Exception
{
    public function render($request)
    {
        Log::add($request, "error", __('blublog.BlublogModulDisabled'));
        return view("blublog::error")->with('error', "BlublogModulDisabled")->with('msg', __('blublog.BlublogModulDisabled'));
    }
}
