<?php

  // load global app
  require_once(realpath(__DIR__ . '/..').'/index.php');

  // load libs
  use App\Respond\Libraries\Utilities;

  // valid JWT token
  $auth = validate_token();
  $email = $auth['email'];
  $id = $auth['id'];

  // get site and user
  $dir = $file = APP_ROOT.'/public/sites/'.$id;

  $arr = array_merge(array('/'), Utilities::listRoutes($dir, $id));

  // send array as json
  send_json($arr);

  ?>