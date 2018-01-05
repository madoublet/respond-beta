<?php

  // load global app
  require_once(realpath(__DIR__ . '/..').'/api/index.php');

  echo(file_get_contents(APP_ROOT.'/public/index.html'));

  ?>
