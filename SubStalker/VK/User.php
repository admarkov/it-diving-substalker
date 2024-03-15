<?php

namespace SubStalker\VK;

class User extends Owner {
  private int $sex;

  public function __construct(int $id, string $name, int $sex) {
    parent::__construct($id, $name);

    $this->sex = $sex;
  }

  public function isMale(): bool {
    return $this->sex === 2;
  }

  public function isFemale(): bool {
    return $this->sex === 1;
  }
}
