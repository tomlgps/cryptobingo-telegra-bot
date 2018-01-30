<?php

namespace App\Services;

class DatabaseServices {

    public static function insert($table, $data) {
        DB::table($table)->insert($data);
    }

    protected static function getNewToken($username) {
        return substr(md5($username), 6, 22);
    }

    public static function registerNewUser($username) {
        $token = self::getNewToken($username);
        self::insert('users', [
            'username' => $username,
            'token' => $token,
            'status' => false
        ]);

        return $token;
    }

}
