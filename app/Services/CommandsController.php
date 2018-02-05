<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CommandsController {

    private static $commands;
    private static $telegram;

    public static function setTelegram($telegram) {
        self::$telegram = $telegram;
    }

    public static function setCommands($commands) {
        self::$commands = $commands;
    }

    public static function execute($message, $user) {
        $message_data = explode(' ', $message);
        print_r($message_data);

        if (in_array($message_data[0], self::$commands)) {
            $command = str_replace('/', '', $message_data[0]);

            if ($command == 'start') {
                self::start($message_data, $user);
            } else {
                echo $command;
                self::$command($user);
            }
        }
    }

    public static function start($message_data, $user) {
        $text = 'Вы уже есть в базе.';
        // можно добавить команду, которая будет выводить правила

        if (! DatabaseService::checkIfUserExsists('username', $user['username'])) {
            if (count($message_data) == 2) {
                $invite_token = $message_data[1];
                $token = DatabaseService::registerNewUser($user, $invite_token);

                InvitationService::activateInviteToken($invite_token);
            } else {
                $token = DatabaseService::registerNewUser($user);
            }

            // $botname не задано, напиши туда короче имя бота
            $text = "Start message. Link: https://t.me/{$botname}?start={$token}"; // start message
        }

        self::$telegram->sendMessage([
            'chat_id' => $user['id'],
            'text' => $text
        ]);
    }

    public static function check_friend($user) {
        $invited = DB::table('users')
            ->where('username', $user['username'])
            ->select('invited')
            ->get()[0]->invited;

        if ($invited) {
            $message = 'Done';
        } else {
            $message = 'Данный пункт правил еще не активирован. ';
        }

        self::$telegram->sendMessage([
            'chat_id' => $user['id'],
            'text' => $message
        ]);
    }

    // public static function subscription_done($user) {
    //     $message = '';
    //     if () { // check выполнено
    //         // update status subscription
    //         if (InvitationService::getStatus($user['username'])) {
    //             $message = 'Все условия выполнены. Теперь вы можете участвовать.';
    //         } else {
    //             $message = 'Окей, красава. Ждем второе условие.';
    //         }
    //
    //         $invite_token = DB::table('users')
    //             ->where('username', $user['username']))
    //             ->select('invite_token')
    //             ->get();
    //
    //         if ($invite_token != 'None') {
    //             InvitationService::updateInviteStatus(
    //                 DB::table('users')
    //                     ->where('token', $invite_token)
    //                     ->select('username')
    //                     ->get()
    //             );
    //
    //             if () { // check
    //
    //             }
    //         }
    //
    //     } else {
    //         $message = 'Нехер врать, пидор.'
    //     }
    //
    //     self::$telegram->sendMessage([
    //         'chat_id' => $user['id'],
    //         'text' => $message
    //     ]);
    // }

}
