<?php

  // load global app
  require_once(realpath(__DIR__ . '/..').'/index.php');

  // dependencies
  use App\Respond\Libraries\Utilities;

  \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

  // get event
  $json = json_decode(file_get_contents("php://input"));

  $type = $json['type'];
  $customer = $json['data']['object']['customer'];

  // handle event types
  if($type == 'charge.failed' || $type == 'invoice.payment_failed') {

    if($customer != NULL) {

      $site = Site::getSiteByCustomerId($customer);

      if($site != NULL) {

        $site->status = 'Active';

        // send email
        $to = $site->email;
        $from = env('EMAILS_FROM');
        $fromName = env('EMAILS_FROM_NAME');
        $subject = env('SUCCESSFUL_CHARGE_SUBJECT', 'Successful Charge');
        $file = app()->basePath().'/resources/emails/failed-charge.html';

        $replace = array(
          '{{brand}}' => env('BRAND'),
          '{{reply-to}}' => env('EMAILS_FROM')
        );

        // send email from file
        Utilities::sendEmailFromFile($to, $from, $fromName, $subject, $replace, $file);
      }
      else {
        send_response(401, 'Site not found');
      }

    }
    else {
      send_response(401, 'Customer not found');
    }

  }
  else if($type == 'charge.succeeded' || $type == 'invoice.payment_succeeded') {

    if($customer != NULL) {

      $site = Site::getSiteByCustomerId($customer);

      if($site != NULL) {

        $site->status = 'Active';

        // send email
        $to = $site->email;
        $from = env('EMAILS_FROM');
        $fromName = env('EMAILS_FROM_NAME');
        $subject = env('SUCCESSFUL_CHARGE_SUBJECT', 'Successful Charge');
        $file = app()->basePath().'/resources/emails/successful-charge.html';

        $replace = array(
          '{{brand}}' => env('BRAND'),
          '{{reply-to}}' => env('EMAILS_FROM')
        );

        // send email from file
        Utilities::sendEmailFromFile($to, $from, $fromName, $subject, $replace, $file);
      }
      else {
        send_response(401, 'Site not found');
      }

    }
    else {
      send_response(401, 'Customer not found');
    }

  }

  return send_success('Ok');

  ?>