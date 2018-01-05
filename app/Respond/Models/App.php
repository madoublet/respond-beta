<?php

namespace App\Respond\Models;

use App\Respond\Libraries\Utilities;

/**
 * Models the app
 */
class App {


  /**
   * Gets the settings for the app
   *
   * @return arr
   */
  public static function settings()
  {

    $has_passcode = true;

    if(env('PASSCODE') === '') {
      $has_passcode = false;
    }

    $language = true;

    if(env('DEFAULT_LANGUAGE') != NULL) {
      $language = env('DEFAULT_LANGUAGE');
    }

    // return app settings
    $arr = array(
      'hasPasscode' => $has_passcode,
      'siteUrl' => Utilities::retrieveSiteURL(),
      'logoUrl' => env('LOGO_URL'),
      'themesLocation' => env('THEMES_LOCATION'),
      'primaryColor' => env('PRIMARY_COLOR'),
      'primaryDarkColor' => env('PRIMARY_DARK_COLOR'),
      'usesLDAP' => !empty(env('LDAP_SERVER')),
      'activationMethod' => env('ACTIVATION_METHOD'),
      'activationUrl' => env('ACTIVATION_URL'),
      'stripeAmount' => env('STRIPE_AMOUNT'),
      'stripeCurrency' => env('STRIPE_CURRENCY'),
      'stripeName' => env('STRIPE_NAME'),
      'stripeDescription' => env('STRIPE_DESCRIPTION'),
      'stripePublishableKey' => env('STRIPE_PUBLISHABLE_KEY'),
      'recaptchaSiteKey' => env('RECAPTCHA_SITE_KEY'),
      'acknowledgement' => env('ACKNOWLEDGEMENT'),
      'defaultLanguage' => $language
    );

    return $arr;
  }


}
