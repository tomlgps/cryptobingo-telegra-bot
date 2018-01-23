<?php

namespace App\Controllers;

class UpdatesController {

    protected $telegram;
    protected $commands_controller;

    public function  __construct($telegram, $commands_controller) {

        $this->telegram = $telegram;
        $this->commands_controller = $commands_controller;

    }


    public function run() {

        $last_update_id = $this->getLastUpdate()['update_id'];

        while (true) {

            $update = $this->getLastUpdate(['offset' => $last_update_id]);

            $get_updates = ($update['update_id']>$last_update_id);

            if ($get_updates) {

                $last_update_id = $update['update_id'];

                $params = $this->getInfoAboutUpdate($update);

                if($params['text']){
                    $this->commands_controller->execute($params);
                } else {
                	$this->telegram->sendMessage([
                        'chat_id' => $params['chat_id'],
                        'text' => "Отправьте текстовое сообщение."
                    ]);
                }

            }

            sleep(0.5);

        }


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




?>
