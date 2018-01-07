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
  $title = filter_var($json->title, FILTER_SANITIZE_STRING);
  $description = filter_var($json->description, FILTER_SANITIZE_STRING);
  $keywords = filter_var($json->keywords, FILTER_SANITIZE_STRING);
  $tags = filter_var($json->tags, FILTER_SANITIZE_STRING);
  $callout = filter_var($json->callout, FILTER_SANITIZE_STRING);
  $language = filter_var($json->language, FILTER_SANITIZE_STRING);
  $direction = filter_var($json->direction, FILTER_SANITIZE_STRING);
  $template = filter_var($json->template, FILTER_SANITIZE_STRING);
  $customHeader = filter_var($json->customHeader, FILTER_SANITIZE_STRING);
  $customFooter = filter_var($json->customFooter, FILTER_SANITIZE_STRING);
  $photo = filter_var($json->photo, FILTER_SANITIZE_STRING);
  $thumb = filter_var($json->thumb, FILTER_SANITIZE_STRING);
  $location = filter_var($json->location, FILTER_SANITIZE_STRING);
  $timestamp = gmdate('D M d Y H:i:s O', time());

  $data = array(
    'title' => $title,
    'description' => $description,
    'keywords' => $keywords,
    'tags' => $tags,
    'callout' => $callout,
    'url' => $url,
    'language' => $language,
    'direction' => $direction,
    'template' => $template,
    'customHeader' => $customHeader,
    'customFooter' => $customFooter,
    'photo' => $photo,
    'thumb' => $thumb,
    'location' => $location,
    'lastModifiedBy' => $email,
    'lastModifiedDate' => $timestamp
  );

  // get site and user
  $site = Site::getById($id);
  $user = User::getByEmail($email, $id);

  // edit the page
  $success = Page::editSettings($data, $site, $user);

  // show response
  if($success == TRUE) {
    send_success('Ok');
  }
  else {
    send_response(400, 'Page not found');
  }

  ?>