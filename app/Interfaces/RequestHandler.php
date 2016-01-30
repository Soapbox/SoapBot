<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface RequestHandler {
    public function handle(Request $request);
}
