<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JsonCheckMiddleware
{
    public function handle(Request $request, Closure $next){

        /*
    When we're recibing a payload, accept it only if it's a Json
        */
        if (in_array($request->method(),['POST', 'PUT']) && 
        	!$request->isJson()
        ) {
        	return response(
                json_encode(array('error'=>"Only JSON data it's accepted")),
                500
            )->header('Content-Type','application/json');
        }

        return $next($request);
    }
}