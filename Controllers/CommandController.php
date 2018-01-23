<?php

namespace App\Controllers;

class CommandController {

    protected $commands = [];
    protected $telegram;

    public function __construct($telegram, $commands = []) {
        $this->telegram = $telegram;
        $this->commands = $commands;
    }

    public function addCommand($command) {
        $this->commands[] = $command;
    }

    public function getCommands() {
        return $this->commands;
    }

    public function execute($arguments) {

        $text = $arguments['text'];

        if (! in_array($text, $this->commands)) {
            return $this->defaultCommand($arguments);
        } else {
            $text = str_replace('/', '', $text);
            return $this->$text($arguments);

        }

    }

    public function start($arguments) {

        $reply = "Ну хеллоу :)";
        $reply_markup = $this->telegram->replyKeyboardMarkup([
            'keyboard' => $arguments['keyboard'],
            'resize_keyboard' => true,
            'one_time_keyboard' => false
        ]);
        $this->telegram->sendMessage([
             'chat_id' => $arguments['chat_id'],
             'text' => $reply,
             'reply_markup' => $reply_markup
        ]);

    }

    public function roll($arguments) {

        $reply = $this->headsOrTails();
        $this->telegram->sendMessage([
            'chat_id' => $arguments['chat_id'],
            'text' => $reply
        ]);

    }

    protected function headsOrTails() {

        if (rand(0, 1)) {
            return 'Орел';
        } else {
            return 'Решка';
        }

    }

    public function help($arguments) {

        $reply = "Команды: \n" . implode("\n", $this->getCommands());
        $this->telegram->sendMessage([
            'chat_id' => $arguments['chat_id'],
            'text' => $reply
        ]);

    }

    public function defaultCommand($arguments) {
        $reply = "По запросу \"<b>" .
            $arguments['text'] . "</b>\"
            ничего не найдено.";
        $this->telegram->sendMessage([
            'chat_id' => $arguments['chat_id'],
            'parse_mode'=> 'HTML',
            'text' => $reply
        ]);
    }


}




?>
