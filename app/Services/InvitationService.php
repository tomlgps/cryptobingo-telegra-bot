<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class InvitationService {

    private static $telegram;

    public static function setTelegram($telegram) {
        self::$telegram = $telegram;
    }

    public static function getNewToken($username) {
        return substr(md5($username), 6, 22);
    }

    public static function activateInviteToken($token) {
        $user = DB::table('users')
            ->where('token', $token);

        if (count($user->get())) {
            $user->update(['status' => true]);

            self::informOwner($user->select('chat_id')->get(), $friend_username);
        }
    }

    private static function informOwner($chat_id, $friend_username) {
        self::$telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => sprintf("%s выполнил необходимые условия.", $friend_username)
        ]);
    }

    public static function updateInviteStatus($username) {
        DB::table('users')
            ->where('username', $username)
            ->update(['invite_status' => true]);
    }

    public static function getStatus($username) {
        return DB::table('users')
            ->where('username', $username)
            ->select('invite_status')
            ->get();
    }

}
