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

  // get url, title and description
  $url = filter_var($json->url, FILTER_SANITIZE_STRING);
  $title = filter_var($json->title, FILTER_SANITIZE_STRING);
  $description = filter_var($json->description, FILTER_SANITIZE_STRING);
  $template = filter_var($json->template, FILTER_SANITIZE_STRING);
  $timestamp = date('Y-m-d\TH:i:s.Z\Z', time());

  // get the site
  $site = Site::getById($id);
  $user = User::getByEmail($email, $id);

  // strip any leading slashes from url
  $url = ltrim($url, '/');

  // strip any trailing .html from url
  $url = preg_replace('/\\.[^.\\s]{3,4}$/', '', $url);

  // set page data
  $data = array(
    'title' => $title,
    'description' => $description,
    'text' => '',
    'keywords' => '',
    'tags' => '',
    'callout' => '',
    'url' => $url,
    'photo' => '',
    'thumb' => '',
    'language' => 'en',
    'direction' => 'ltr',
    'firstName' => $user->firstName,
    'lastName' => $user->lastName,
    'lastModifiedBy' => $user->email,
    'lastModifiedDate' => $timestamp,
    'template' => $template
  );

  // add a page
  $page = Page::add($data, $site, $user);

  if($page != NULL) {

    // re-publish plugins
    Publish::publishPlugins($user, $site);

    // re-publish site map
    Publish::publishSiteMap($user, $site);

    // re-publish the settings
    Publish::publishSettings($user, $site);

    // return OK
    send_success('Ok');

  }
  else {
    send_response(400, 'Page not created');
  }

  ?>