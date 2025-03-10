<?php

namespace App\Repositories\Contracts;
interface IChat {

   public function createParticipant($chatId, array $data);

   public function getUserChats();
}
