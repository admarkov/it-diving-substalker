<?php

namespace SubStalker;

use Generator\Skeleton\skeleton\base\src\VK\CallbackApi\VKCallbackApiLongPollExecutor;
use SubStalker\VK\VKClient;
use VK\Client\VKApiClient;

class SubStalker {
  private VKApiClient $client;
  private VKClient $vk;
  private CallbacksHandler $handler;
  private VKCallbackApiLongPollExecutor $executor;

  public function __construct(int $group_id, int $recepient_user_id, string $access_token) {
    $this->client = new VKApiClient('5.131');
    $this->vk = new VKClient($access_token, $this->client);
    $this->handler = new CallbacksHandler($this->vk, $recepient_user_id);
    $this->executor = new VKCallbackApiLongPollExecutor(
      $this->client,
      $access_token,
      $group_id,
      $this->handler
    );
  }


  public function listen() {
    $ts = time();
    while (true) {
      try {
        $ts = $this->executor->listen($ts);
      } catch (\Exception) {
      }
    }
  }
}
