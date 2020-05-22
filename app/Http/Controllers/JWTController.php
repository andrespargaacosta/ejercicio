<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use \Firebase\JWT\JWT;


class JWTController extends BaseController{


	private static $privateKey = false;
	private static $publicKey = false;
	private static $algo = false;
	private static $expireTime = 30;

    public function __construct(){
    	self::$publicKey = Storage::disk('local')->get(getenv('JWT_AUTH_PRIVATE_KEY') );
    	self::$privateKey = Storage::disk('local')->get(getenv('JWT_AUTH_PRIVATE_KEY') );
    	self::$algo = getenv('JWT_AUTH_ALGORITHM');
    	self::$expireTime = getenv('JWT_TOKEN_LIFETIME');
    }

    public function getToken(Request $request){
    	return response( 
    		$this->createToken($request), 
    		200 )
    	->header(
    		'Content-Type', 
    		'application/json'
    	);
    }


    public function createToken(Request $request){
    	$iat = date("U");
    	$payload = array (
			'name' => 'EXAMPLE',
			//'jti' => 'c742e156-b995-4bda-b546-e08e877ee93b',
			'iat' => intval($iat),
			'exp' => $iat+(self::$expireTime*60*1000),
		);

		$JWT = JWT::encode($payload,self::$privateKey,self::$algo);
		return $JWT;
    }

    public function test(Request $request){
    	echo "success";
    }


}