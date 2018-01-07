<?php

  // load global app
  require_once(realpath(__DIR__ . '/..').'/index.php');

  // load libs
  use App\Respond\Libraries\Publish;
  use App\Respond\Models\User;
  use App\Respond\Models\Site;
  use App\Respond\Models\Page;

  // valid JWT token
  $auth = validate_token();
  $email = $auth['email'];
  $id = $auth['id'];

  // get url & changes
  $url = filter_var($json->url, FILTER_SANITIZE_STRING);

  // get site and user
  $site = Site::getById($id);
  $user = User::getByEmail($email, $id);

  // get page
  $page = Page::getByUrl($url, $id);

  $page->remove($user, $site);

  // re-publish site map
  Publish::publishSiteMap($user, $site);

  // return OK
  send_success('Ok');


  ?>