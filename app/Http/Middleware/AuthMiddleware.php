<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use \Firebase\JWT\JWT;


class AuthMiddleware{

	private static $publicKey = false;
	private static $algo = false;

    public function __construct(){
    	self::$publicKey = Storage::disk('local')->get(getenv('JWT_AUTH_PRIVATE_KEY') );
    	self::$algo = getenv('JWT_AUTH_ALGORITHM');
    }

    public function handle(Request $request, Closure $next){

    	$decoded = '';
        if (!$request->headers->get('authorization')){
        	return response(json_encode(array('error'=>"Unauthorized"),401))->header('Content-Type','application/json');
        }

        $jwt = explode(' ',$request->headers->get('authorization'));

	    	try{
		    	$decoded = JWT::decode($jwt[1], self::$publicKey, array(self::$algo) );
		    }catch(\Exception $e){
		    	return response(
		    		json_encode( array('error'=>$e->getMessage() ) ),
		    		401)
		    	->header(
		    		'Content-Type', 
		    		'application/json'
		    	);
		    }

        return $next($request);
    }
}