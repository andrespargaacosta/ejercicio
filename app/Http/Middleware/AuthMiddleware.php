<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use \Firebase\JWT\JWT;


class AuthMiddleware{


    /*
    Our env vars and keys, stored into private static vars for performance:

    $publicKey : our public key, the filename was defined in the env file as "JWT_AUTH_PRIVATE_KEY"
    $algo : our signing algorithm, defined in the env file as "JWT_AUTH_ALGORITHM"
    
    */
	private static $publicKey = false;
	private static $algo = false;
    
    /*
    we're initializing the class and getting the requiered env variables & keys
    */
    public function __construct(){
    	self::$publicKey = Storage::disk('local')->get(getenv('JWT_AUTH_PRIVATE_KEY') );
    	self::$algo = getenv('JWT_AUTH_ALGORITHM');
    }

    /*
    Here we check the sent JWT:
    1.- First, if  it's present
    2.- Then, if it's signature it's valid
    3.- Finally, if hasn't expired
    */
    public function handle(Request $request, Closure $next){

    	$decoded = '';
        if (!$request->headers->get('authorization')){
        	return response(json_encode(array('error'=>"Unauthorized")),401)->header('Content-Type','application/json');
        }

        $jwt = explode(' ',$request->headers->get('authorization'));

	    	try{
		    	$decoded = JWT::decode($jwt[1], self::$publicKey, array(self::$algo) );
		    }catch(\Exception $e){
		    	return response(json_encode( array('error'=>$e->getMessage() ) ),401)->header('Content-Type', 'application/json');
		    }

        return $next($request);
    }
}