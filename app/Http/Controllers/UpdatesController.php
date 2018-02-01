<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommandsController;
use Telegram\Bot\Api;

class UpdatesController extends Controller
{

    public static function run() {
<<<<<<< HEAD
        $telegram = new Api('421975949:AAHBU7Iyts-4HNZ2dOHgU2Fcm1MHH1I9bAY');
=======
        $telegram = new Api('token');
>>>>>>> subscriptions
        //
        // $data = $telegram->getWebhookUpdates();
        // var_dump($data);

        CommandsController::setTelegram($telegram);
        CommandsController::setCommands(['/start', '/check_friend']);

        $last_update_id = 558867787;

        while (true) {
            $update = $telegram->getUpdates(['offset' => $last_update_id + 1])[0] ?? 'none';
            // die(print_r($update));
            if ($update=='none'){
                continue;
            }

            $get_updates = (($update['update_id'] ?? -1) > $last_update_id);

            echo $last_update_id;

            if ($get_updates) {
                print_r($update);

                $last_update_id = $update['update_id'];

                $message = $update['message']['text'];
                $user = $update['message']['from'];

                if($message){
                    CommandsController::execute($message, $user);
                } else {
                	$telegram->sendMessage([
                        'chat_id' => $user['id'],
                        'text' => "Отправьте текстовое сообщение."
                    ]);
                }
            }

        }
<<<<<<< HEAD
=======


>>>>>>> subscriptions
    }

    protected function getInfoAboutUpdate($update) {

        $keyboard = [];
        foreach ($this->commands_controller->getCommands() as $command) {
            $keyboard[] = [$command];
        }

        return [
            'text' => $update["message"]["text"],
            'chat_id' => $update["message"]["chat"]["id"],
            'name' => $update["message"]["from"]["username"],
            'keyboard' => $keyboard
        ];


    }

    protected function getLastUpdate($arguments = []) {

        $updates = $this->telegram->getUpdates($arguments);

        if (count($updates)) {
            return $updates[count($updates) - 1];
        } else {
            return $updates;
        }

    }

}
