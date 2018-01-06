<?php

  // load global app
  require_once(realpath(__DIR__ . '/..').'/index.php');

  // dependencies
  use App\Respond\Models\Site;

  // get request
  $siteId = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
  $key = filter_var($_POST['key'], FILTER_SANITIZE_STRING);

  if($key == env('APP_KEY')) {

    $site = Site::getById($siteId);
    $site->activate();

    send_success('Ok');
  }
  else {
    send_response(401, 'Passcode invalid');
  }

  ?>