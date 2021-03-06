<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DatabaseService {

    public static function insert($table, $data) {
        DB::table($table)->insert($data);
    }

    public static function registerNewUser($user, $invite_token = 'None') {
        $token = InvitationService::getNewToken($user['username']);
        self::insert('users', [
            'chat_id' => $user['id'],
            'username' => $user['username'],
            'token' => $token,
            'invited' => 0,
            'invite_token' => $invite_token
        ]);

        if ($invite_token != 'None')

        return $token;
    }

    public static function checkIfUserExsists($column, $value) {
        $users_with_the_same_value = count(
            DB::table('users')->where($column, $value)->get()->toArray()
        );

        if ($users_with_the_same_value) {
            return true;
        }
        return false;
    }

    public static function addToQueue($user_id) {
        $user = DB::table('users')->where('id', $user_id)->get()->toAray();

        self::insert('queue', [
            'username' => $user['username'],
            'token'=> $user['token'],
            'chat_id' => $user['chat_id']
        ]);
    }

}
