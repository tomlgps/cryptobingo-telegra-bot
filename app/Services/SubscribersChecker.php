<?php

namespace App\Services;

use \Curl\Curl;

class SubscribersChecker
{

  protected static $group_name = ['@cryptobingo', '@cryptomorrow', '@coinbanger', '@coinstrike'];

  // public static function getId($username)
  // {
  //     $botToken=env('BOT_TOKEN');
  //     $website="https://api.telegram.org/bot".$botToken;
  //     $update=file_get_contents($website."/getupdates");
  //     $updateArray=json_decode($update,true);
  //     for ($i=0;$i>93;$i++)
  //     {
  //         if($updateArray["result"][$i]["message"]["from"][username]=$username)
  //             $text=$updateArray["result"][$i]["message"]["from"][id]=$user_id;
  //     }
  // }

  public static function getChatMember($params)
   {
     $params = [
       'chat_id' => '',
       'user_id' => '',
    ];
       $response = $this->post('getChatMember', $params);
       return new ChatMember($response->getDecodedBody());
  }



}
