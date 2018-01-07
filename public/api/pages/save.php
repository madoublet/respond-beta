<?php

  // load global app
  require_once(realpath(__DIR__ . '/..').'/index.php');

  // load libs
  use App\Respond\Libraries\Utilities;
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
  $changes = $json->changes;

  // get site and user
  $site = Site::getById($id);
  $user = User::getByEmail($email, $id);

  // remove site and .html from url
  $url = str_replace($id.'/', '', $url);
  $url = preg_replace('/\\.[^.\\s]{3,4}$/', '', $url);

  // edit the page
  $success = Page::edit($url, $changes, $site, $user);

  // show response
  if($success == TRUE) {

    // re-publish plugins
    Publish::publishPlugins($user, $site);

    // re-publish site map
    Publish::publishSiteMap($user, $site);

    send_success('Ok');

  }
  else {
    send_response(400, 'Page not found');
  }


  ?>