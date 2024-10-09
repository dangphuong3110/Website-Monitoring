<?php

namespace App\Library;

use \Firebase\JWT\JWT;

class JwtLibrary
{
    var $key = 'Zo202019IOMCONONSS';
    var $private_key = 'inetprivatekeyjwt';

    /**
     * @return string
     */
    function jwtEncode($token)
    {
        if (is_array($token)) {
//            $token['iss'] = function_exists('site_url')
//                ? url()
//                : $_SERVER[config('app.url')];
            $token['iss'] = 'https://sso.inet.vn';
            $token['iat'] = '1356999524';
            //$token['nbf'] = '1357000000';
        }

        /**
         * IMPORTANT:
         * You must specify supported algorithms for your application. See
         * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
         * for a list of spec-compliant algorithms.
         */
        $jwt = JWT::encode($token, $this->private_key, 'HS256');

        return $jwt;
    }

    /**
     * @param $id
     * @return object
     */
    function jwtDecode($id)
    {
        JWT::$leeway = 50;

        $decoded = JWT::decode($id, $this->key, array('HS256'));

        return $decoded;
    }
}
