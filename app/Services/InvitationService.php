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
        if (DatabaseSevice::checkIfUserExsists('token', $token)) {
            $user = DB::table('users')->where('token', $token);
            $user->update(['invited' => $user->get()[0]->invited + 1]);

            self::informOwnerAboutActivation($user->get()[0]->chat_id, $friend_username);
            self::checkAndSendIfDone($user->get()[0]);
        }
    }

    private static function checkAndSendIfDone($user) {
        if ($user->invited == 2) {
            self::$telegram->sendMessage([
                'chat_id' => $user->chat_id,
                'text' => 'Теперь вы участвуете.'
            ]);
        }
    }

    private static function informOwnerAboutActivation($chat_id, $friend_username) {
        self::$telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => "{$friend_username} выполнил необходимые условия."
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
