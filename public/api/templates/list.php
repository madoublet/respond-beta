<?php

  // load global app
  require_once(realpath(__DIR__ . '/..').'/index.php');

  // load libs
  use App\Respond\Models\Site;
  use App\Respond\Libraries\Utilities;

  // valid JWT token
  $auth = validate_token();
  $email = $auth['email'];
  $id = $auth['id'];

  $site = Site::getById($id);

  // set dir
  $dir = APP_ROOT.'/public/sites/'.$site->id.'/templates';

  // list files
  $files = Utilities::ListFiles($dir, $site->id,
          array('html'),
          array());


  // get template
  foreach($files as &$file) {

    $file = basename($file);
    $file = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);

  }

  // send array as json
  send_json($files);

  ?>