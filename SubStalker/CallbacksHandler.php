<?php

namespace SubStalker;

use SubStalker\VK\Owner;
use SubStalker\VK\VKClient;
use VK\CallbackApi\VKCallbackApiHandler;

class CallbacksHandler extends VKCallbackApiHandler {
  private VKClient $vk;
  private int $notifications_receiver;
  private Notifier $notifier;

  public function __construct(VKClient $vk, int $recepient_user_id) {
    $this->vk = $vk;
    $this->notifications_receiver = $recepient_user_id;
    $this->notifier = new Notifier($vk);
  }

  public function groupLeave(int $group_id, ?string $secret, array $object) {
    $user_id = (int)$object['user_id'];
    $this->notifier->notifyLeave($this->notifications_receiver, $user_id, $group_id);
  }

  public function groupJoin(int $group_id, ?string $secret, array $object) {
    $user_id = (int)$object['user_id'];
    $this->notifier->notifyJoin($this->notifications_receiver, $user_id, $group_id);
  }
}
