<?php

  // load global app
  require_once(realpath(__DIR__ . '/..').'/index.php');

  // load libs
  use App\Respond\Models\Site;
  use App\Respond\Models\File;
  use App\Respond\Libraries\Utilities;

  // valid JWT token
  $auth = validate_token();
  $email = $auth['email'];
  $id = $auth['id'];

  // get a reference to the site
  $site = Site::getById($id);

  // list files
  $arr = File::listFiles($id);

  // set image extensions
  $image_exts = array('gif', 'png', 'jpg', 'svg');

  $files = array();

  foreach($arr as $file) {

    $filename = str_replace('files/', '', $file);

    $path = APP_ROOT.'/public/sites/'.$id.'/files/'.$filename;

    // get extension
    $parts = explode(".", $filename);
    $ext = end($parts); // get extension
    $ext = strtolower($ext); // convert to lowercase

    // determine if it is an image
    $is_image = in_array($ext, $image_exts);

    // get the filesize
    $size = filesize($path);

    if($is_image === TRUE) {
      $width = 0;
      $height = 0;

      try{
        list($width, $height, $type, $attr) = Utilities::getImageInfo($path);
      }
      catch(Exception $e){}

      // set url, thumb
      $url = $thumb = 'sites/'.$site->id.'/files/'.$filename;

      // check for thumb
      if(file_exists(APP_ROOT.'/public/sites/'.$id.'/files/thumbs/'.$filename)) {
        $thumb = 'sites/'.$site->id.'/files/thumbs/'.$filename;
      }

      // push file to the array
      array_push($files, array(
        'name' => $filename,
        'url' => $url,
        'thumb' => $thumb,
        'extension' => $ext,
        'isImage' => $is_image,
        'width' => $width,
        'height' => $height,
        'size' => number_format($size / 1048576, 2)
      ));

    }
    else {

      // push file to the array
      array_push($files, array(
        'name' => $filename,
        'url' => 'files/'.$filename,
        'thumb' => '',
        'extension' => $ext,
        'isImage' => $is_image,
        'width' => NULL,
        'height' => NULL,
        'size' => number_format($size / 1048576, 2)
      ));

    }

  }

  // send array as json
  send_json($files);

  ?>