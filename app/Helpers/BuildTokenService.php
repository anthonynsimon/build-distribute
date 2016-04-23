<?php

namespace App\Helpers;

use Config;
use \Firebase\JWT\JWT;

class BuildTokenService
{
	public static function generateBuildToken($buildId) {
		$key = Config::get('app.key');
		
		$token = array(
				'buildId' => $buildId
		);
		
		$jwt = JWT::encode($token, $key);
				
		return rtrim(strtr(base64_encode($jwt), '+/', '-_'), '=');
	}
	
	public static function validateTokenAndGetBuildId($data) {
		try {
			$jwt = base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
			$key = Config::get('app.key');
			$decoded = JWT::decode($jwt, $key, array('HS256'));
		}
		catch(\Exception $e){
			abort(403);
		}
		return $decoded->buildId;
	}
}