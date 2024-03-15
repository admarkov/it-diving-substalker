<?php

namespace SubStalker\VK;

use VK\Client\VKApiClient;

class VKClient {
  private string $access_token;
  private VKApiClient $client;

  public function __construct(string $access_token, VKApiClient $client) {
    $this->access_token = $access_token;
    $this->client = $client;
  }

  public function getUser(int $user_id): ?User {
    try {
      $response = $this->client->users()->get($this->access_token, [
        'user_ids' => [$user_id],
        'fields' => ['sex'],
      ]);
    } catch (\Exception $e) {
      var_dump($e);
      return null;
    }

    return new User(
      $user_id,
      $response[0]['first_name'] . ' ' . $response[0]['last_name'],
      (int)$response[0]['sex']
    );
  }

  public function getGroup(int $group_id): ?Group {
    try {
      $response = $this->client->groups()->getById($this->access_token, [
        'group_id' => $group_id,
      ]);
    } catch (\Exception $e) {
      var_dump($e);
      return null;
    }

    return new Group(
      $group_id,
      $response[0]['name'],
    );
  }

  public function sendMessage(int $to_id, string $text) {
    try {
      $_response = $this->client->messages()->send($this->access_token, [
        'peer_id' => $to_id,
        'message' => $text,
        'random_id' => rand(),
      ]);
    } catch (\Exception $e) {
      var_dump($e);
    }
  }
}
