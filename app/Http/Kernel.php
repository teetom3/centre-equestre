<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\GerantMiddleware;

class Kernel extends HttpKernel
{
    // ...

    protected $routeMiddleware = [
        // ...
        'gérant' => GerantMiddleware::class,
        
    ];


    
}