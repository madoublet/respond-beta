<?php

  // load global app
  require_once(realpath(__DIR__ . '/..').'/index.php');

  // load libs
  use App\Respond\Models\User;
  use App\Respond\Models\Site;
  use App\Respond\Models\Page;

  // valid JWT token
  $auth = validate_token();
  $email = $auth['email'];
  $id = $auth['id'];

  // get site and user
  $site = Site::getById($id);
  $user = User::getByEmail($email, $id);

  // list pages in the site
  $arr = Page::listAll($user, $site);

  // sort by last modified date
  usort($arr, function($a, $b) {
      $ts1 = strtotime($a['lastModifiedDate']);
      $ts2 = strtotime($b['lastModifiedDate']);
      return $ts2 - $ts1;
  });

  // send array as json
  send_json($arr);

  ?>