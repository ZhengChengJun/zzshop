<?php

namespace App\Models;

class M3Result {

  public $status;
  public $message;

  public function toJson()
  {
    return json_encode($this);
  }

}
