<?php

  // load global app
  require_once(realpath(__DIR__ . '/..').'/index.php');

  // list pages in the site
  $file = APP_ROOT.'/public/assets/i18n/languages.json';

  $result = array();

  if(file_exists($file)) {

    $json = file_get_contents($file);
    $result = json_decode($json);

  }

  send_json($result);

  ?>