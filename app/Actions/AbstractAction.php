<?php

namespace App\Actions;

use Closure;

abstract class AbstractAction
{
    public function then(Closure $callback)
    {
        return app()->call($callback);
    }
}
