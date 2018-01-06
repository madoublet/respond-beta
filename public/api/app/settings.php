<?php

  // load global app
  require_once(realpath(__DIR__ . '/..').'/index.php');

  // dependencies
  use App\Respond\Models\App;

  $arr = App::settings();

  send_json($arr);

  ?>