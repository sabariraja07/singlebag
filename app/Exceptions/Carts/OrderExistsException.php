<?php

namespace App\Exceptions\Carts;

use Illuminate\Http\Request;

class OrderExistsException extends CartException
{
    public function __construct($messageBag = null)
    {
    }
    public function render(Request $request)
    {
        return response()->json(['message' => "errro"]);
    }
}
