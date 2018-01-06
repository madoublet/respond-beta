<?php

  // load global app
  require_once(realpath(__DIR__ . '/..').'/index.php');

  // dependencies
  use App\Respond\Models\User;
  use App\Respond\Models\Site;
  use App\Respond\Models\Setting;
  use App\Respond\Libraries\Utilities;

  // get json
  $json = json_decode(file_get_contents("php://input"));

  // get paramters
  $email = filter_var($json->email, FILTER_VALIDATE_EMAIL);
  $password = filter_var($json->password, FILTER_SANITIZE_STRING);
  $id = '';

  // try to get an id if passed
  if(isset($json->id)) {
    $id = filter_var($json->id, FILTER_SANITIZE_STRING);
  }

  // lookup site id for user
  if(isset($id) == FALSE || $id == '') {

    $arr = User::lookupUserByEmail($email);

    // set site id
    if(sizeof($arr) == 1) {
      $id = $arr[0];
    }
    else if(sizeof($arr) == 0) {
      send_response(401, 'The email and password combination is invalid.');
    }
    else {
      send_response(409, 'You have multiple sites registered with this email.');
    }
  }

  // get site by its friendly id
  $site = Site::getById($id);

  if ($site != NULL) {

    // get the user from the credentials
    $user = NULL;

    // if LDAP is being used, check for @ to determine how to authenticate user
    if(strpos($email, '@') === false && !empty(env('LDAP_SERVER'))){
      //authenticate against LDAP, on success get user by 'email'
      $ldap = ldap_connect(env('LDAP_SERVER'));
      if($bind = ldap_bind($ldap, env('LDAP_DOMAIN') . '\\' . $email)) {
        $user = User::getByEmail($email, $site->id);
      }
    }
    else {
      // get the user from the credentials
      $user = User::getByEmailPassword($email, $password, $site->id);
    }

    if($user != NULL) {

      // get the photoURL
      $fullPhotoUrl = '';

    	// set photo url
    	if($user->photo != '' && $user->photo != NULL){

    		// set images URL
        $imagesURL = $site->domain;
      	$fullPhotoUrl = $imagesURL.'/files/thumbs/'.$user->photo;

    	}

    	$activationUrl = '';

    	if(env('ACTIVATION_URL') != NULL) {
      	$activationUrl = env('ACTIVATION_URL');
      	$activationUrl = str_replace('{{site}}', $site->id, $activationUrl);
    	}

    	// determine if a customer has an account
    	$hasAccount = false;

    	if($site->status == 'Active' && $site->customerId != '') {
      	$hasAccount = true;
    	}

    	// determine if site can be synced
    	$can_sync = false;
    	$sync_type = '';

    	$sync = Setting::getById('sync', $site->id);

      // make sure sync is set
      if($sync != NULL) {

        // ... and check to make sure it is not empty
        if($sync != '') {
          $can_sync = true;
          $sync_type = $sync;
        }
      }

      // return a subset of the user array
      $returned_user = array(
      	'email' => $user->email,
      	'firstName' => $user->firstName,
      	'lastName' => $user->lastName,
      	'photo' => $user->photo,
      	'fullPhotoUrl' => $fullPhotoUrl,
      	'language' => $user->language,
      	'siteId' => $site->id,
      	'status' => $site->status,
      	'hasAccount' => $hasAccount,
      	'days'=> $site->daysRemaining(),
      	'activationUrl'=> $activationUrl
      );

      // send token
      $arr = array(
      	'user' => $returned_user,
      	'sync' => array(
          	'canSync' => $can_sync,
          	'syncType' => $sync_type
        	),
      	'token' => Utilities::createJWTToken($user->email, $site->id)
      );

      // return json response
      send_json($arr);

    }
    else {
       send_response(401, 'Unauthorized');
    }
  }
  else {
     send_response(401, 'Unauthorized');
  }


  ?>