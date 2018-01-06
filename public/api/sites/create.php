<?php

  // load global app
  require_once(realpath(__DIR__ . '/..').'/index.php');

  // load model
  use App\Respond\Models\Site;
  use App\Respond\Libraries\Utilities;

  // get json
  $json = json_decode(file_get_contents("php://input"));

  // get request
  $name = filter_var($json->name, FILTER_SANITIZE_STRING);
  $theme = filter_var($json->theme, FILTER_SANITIZE_STRING);
  $email = filter_var($json->email, FILTER_VALIDATE_EMAIL);
  $password = filter_var($json->password, FILTER_SANITIZE_STRING);
  $passcode = filter_var($json->passcode, FILTER_SANITIZE_STRING);
  $gRecaptchaResponse = filter_var($json->recaptchaResponse);

  // handle reCAPTCHA
  if(isset($gRecaptchaResponse)) {

    $secret = env('RECAPTCHA_SECRET_KEY');

    // check if secret is set
    if(isset($secret)) {

      // do not check if secret is empty
      if($secret != '') {

        $recaptcha = new \ReCaptcha\ReCaptcha($secret);
        $remoteIp = $request->ip();

        $resp = $recaptcha->verify($gRecaptchaResponse, $remoteIp);

        if ($resp->isSuccess()) {
          // verified! continue
        } else {
            $errors = $resp->getErrorCodes();
            send_response(401, 'reCAPTCHA invalid');
        }

      }

    }
    else {
      send_response(401, 'reCAPTCHA error no secret');
    }

  }

  if($passcode == env('PASSCODE')) {

    $arr = Site::create($name, $theme, $email, $password);

    // send email
    $to = $email;
    $from = env('EMAILS_FROM');
    $fromName = env('EMAILS_FROM_NAME');
    $subject = env('CREATE_SUBJECT', 'New Site');
    $file = APP_ROOT.'/resources/emails/create-site.html';

    // create strings to replace
    $loginUrl = Utilities::retrieveAppURL();
    $siteUrl = str_replace('{{siteId}}', $arr['id'],  Utilities::retrieveSiteURL());

    $replace = array(
      '{{brand}}' => env('BRAND'),
      '{{reply-to}}' => env('EMAILS_FROM'),
      '{{new-site-url}}' => $siteUrl,
      '{{login-url}}' => $loginUrl
    );

    // send email from file
    Utilities::sendEmailFromFile($to, $from, $fromName, $subject, $replace, $file);

    send_json($arr);
  }
  else {
    send_response(401, 'Passcode invalid');
  }

  ?>