<?php


require 'vendor/autoload.php';

use Telegram\Bot\Api;
use App\Controllers\{CommandController, UpdatesController};


$token = '421975949:AAHBU7Iyts-4HNZ2dOHgU2Fcm1MHH1I9bAY';

$telegram = new Api($token);


$updates_controller = new UpdatesController(
    $telegram,
    new CommandController($telegram, ['/start', '/roll', '/help'])
);

$updates_controller->run();


?>
