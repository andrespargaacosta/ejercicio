<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use \Firebase\JWT\JWT;


class JWTController extends BaseController{

	/*
	Our env vars and keys, stored into private static vars for performance:

	$privateKey : our private key, the filemane was defined in the env file as "JWT_AUTH_PRIVATE_KEY"
	$algo : our signing algorithm, defined in the env file as "JWT_AUTH_ALGORITHM"
	$expireTime : our JWT lifetime in minutes, defined in the env file as "JWT_TOKEN_LIFETIME" 
	
	*/
	private static $privateKey = false;
	private static $algo = false;
	private static $expireTime = 30;

	/*
	we're initializing the class and getting the requiered env variables & keys
	*/
    public function __construct(){
    	self::$privateKey = Storage::disk('local')->get(getenv('JWT_AUTH_PRIVATE_KEY') );
    	self::$algo = getenv('JWT_AUTH_ALGORITHM');
    	self::$expireTime = getenv('JWT_TOKEN_LIFETIME');
    }

    public function getToken(Request $request){
    	return response( $this->createToken($request), 200 )->header('Content-Type', 'application/json');
    }

    /*
    Here we're creating the JWT, using a private key file, which location was defined in the env file and the key itself was retrieved into the private var "privateKey" 
    */
    protected function createToken(Request $request){
    	$iat = date("U");
    	$payload = array (
			'name' => 'EXAMPLE',
			'iat' => intval($iat),
			'exp' => $iat+(self::$expireTime*60),
		);

		$JWT = JWT::encode($payload,self::$privateKey,self::$algo);
		return $JWT;
    }

}