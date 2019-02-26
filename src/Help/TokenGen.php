<?php
/**
 * Created by PhpStorm.
 * User: Laidy
 * Date: 25/02/2019
 * Time: 10:44
 */

namespace App\Help;


use Exception;

class TokenGen
{
    public static function generateToken(): String {
        $token = "";
        try {
            $token = bin2hex(random_bytes(5));
        } catch (Exception $e) {
        }

        return $token;
    }
}