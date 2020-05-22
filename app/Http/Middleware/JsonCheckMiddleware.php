<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JsonCheckMiddleware
{
    public function handle(Request $request, Closure $next){

        if (
        	in_array(
        		$request->method(), 
        		['POST', 'PUT', 'DELETE']
        	) && 
        	!$request->isJson()
        ) {
        	return response(json_encode(array('error'=>"Only JSON data it's accepted"),500))->header('Content-Type','application/json');
        }

        return $next($request);
    }
}