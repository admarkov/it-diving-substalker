<?php

namespace SubStalker;

use SubStalker\VK\Group;
use SubStalker\VK\Owner;
use SubStalker\VK\User;
use SubStalker\VK\VKClient;

class Notifier {
  private VKClient $vk;

  private const NOTIFICATION_TYPE_JOIN = 'join';
  private const NOTIFICATION_TYPE_LEAVE = 'leave';

  public function __construct(VKClient $vk) {
    $this->vk = $vk;
  }

  public function notifyJoin(int $receiver_id, int $user_id, int $group_id) {
    $this->notify(self::NOTIFICATION_TYPE_JOIN, $receiver_id, $user_id, $group_id);
  }

  public function notifyLeave(int $receiver_id, int $user_id, int $group_id) {
    $this->notify(self::NOTIFICATION_TYPE_LEAVE, $receiver_id, $user_id, $group_id);
  }

  private function notify(string $type, int $receiver_id, int $user_id, int $group_id) {
    $user = $this->vk->getUser($user_id);
    if (!$user) {
      echo "failed to load user\r\n";
      return;
    }

    $group = $this->vk->getGroup($group_id);
    if (!$group) {
      echo "failed to load group\r\n";
      return;
    }

    $text = $this->buildText($type, $user, $group);

    $this->vk->sendMessage($receiver_id, $text);
  }

  private function buildMention(Owner $owner) {
    $prefix = ($owner instanceof User) ? 'id' : 'club';
    return "[{$prefix}{$owner->getId()}|{$owner->getName()}]";
  }

  private function buildText(string $type, User $user, Group $group): string {
    $user_mention = self::buildMention($user);
    $group_mention = self::buildMention($group);
    switch ($type) {
      case self::NOTIFICATION_TYPE_JOIN:
        if ($user->isFemale()) {
          $action_string = 'подписалась';
        } else {
          $action_string = 'подписался';
        }
        return "{$user_mention} {$action_string} на сообщество {$group_mention} :)";
      case self::NOTIFICATION_TYPE_LEAVE:
        if ($user->isFemale()) {
          $action_string = 'покинула';
        } else {
          $action_string = 'покинул';
        }
        return "{$user_mention} {$action_string} сообщество {$group_mention} :(";
      default:
        return "Пришло событие непонятного типа :|";
    }
  }

}
