<?php

namespace Blublog\Blublog\Exceptions;

use Blublog\Blublog\Models\Log;
use Exception;

class BlublogNotFound extends Exception
{
    public function render($request)
    {
        Log::add($request, "error", __('blublog.BlublogNotFound'));
        return view("blublog::error")->with('error', "BlublogNotFound")->with('msg', __('blublog.BlublogNotFound'));
    }
}
