<?php

namespace SubStalker\VK;

class Group extends Owner {
  public function __construct(int $id, string $name) {
    parent::__construct($id, $name);
  }
}
