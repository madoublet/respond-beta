<?php

  // load global app
  require_once(realpath(__DIR__ . '/..').'/index.php');

  // dependencies
  use App\Respond\Models\Site;

  // valid JWT token
  $auth = validate_token();
  $email = $auth['email'];
  $id = $auth['id'];

  // get token
  $json = json_decode(file_get_contents("php://input"));
  $stripeToken = filter_var($json->token, FILTER_SANITIZE_STRING);
  $stripeEmail = filter_var($json->email, FILTER_SANITIZE_STRING);

  // get site
  $site = Site::getById($siteId);

  try {

    // #ref https://stripe.com/docs/recipes/subscription-signup
    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

    $customer = \Stripe\Customer::create(array(
      'email' => $stripeEmail,
      'source'  => $stripeToken,
      'plan' => env('STRIPE_PLAN')
    ));

    // activate
    $site->status = 'Active';
    $site->customerId = $customer->id;
    $site->save();

    send_success('Ok');
  }
  catch(Exception $e)
  {
    send_response(401, 'Unable to subscribe');
  }

  ?>