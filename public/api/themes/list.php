<?php

  // load global app
  require_once(realpath(__DIR__ . '/..').'/index.php');

  // dependencies
  use App\Respond\Libraries\Utilities;

  // list pages in the site
  $dir = APP_ROOT.'/public/'.env('THEMES_LOCATION');

  // list files
  $arr = Utilities::listSpecificFiles($dir, 'theme.json');

  $result = array();

  foreach ($arr as $item) {

    // get contents of file
    $json = json_decode(file_get_contents($item));

    // get location of theme
    $temp = explode(getenv('THEMES_LOCATION'), $item);
    $location = substr($temp[1], 0, strpos($temp[1], '/theme.json'));

    $json->location = $location;

    array_push($result, $json);

  }

  send_json($result);

  ?>