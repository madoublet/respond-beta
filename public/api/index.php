<?php

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  /************************************/
	/*  CONSTANTS                       */
	/************************************/

	define('HTTP_OK', 200);
	define('HTTP_BAD_REQUEST', 400);
	define('HTTP_TYPE_TEXT_HTML', 'text/html');
	define('HTTP_TYPE_JSON', 'application/json');
	define('APP_ROOT', realpath(__DIR__ . '/../..'));
	define('DEBUG', FALSE);

	/************************************/
	/*  SETUP LIBS                      */
	/************************************/

	require_once(APP_ROOT.'/vendor/autoload.php');


	$dotenv = new Dotenv\Dotenv(APP_ROOT);
  $dotenv->load();

  /************************************/
	/*  DEBUGGING                       */
	/************************************/

	if(getenv('DEBUG') == true){
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
	}

  /************************************/
	/*  Common functions                */
	/************************************/

	function env($var, $default = '') {

  	if(getenv($var) == FALSE) {
    	return $default;
  	}
  	else {
    	return getenv($var);
  	}

	}

	function send_response($code, $text = '', $type = 'text/html') {

	    // set response code
	    http_response_code($code);

	    // set type
     	header('Content-Type: '.$type);

     	// set text if provided
     	if($text != ''){
	     	print $text;
     	}

     	exit();
  }

  function send_json($arr) {

    // set response code
    http_response_code(200);

    // set type
   	header('Content-Type: application/json');

   	// json
   	print json_encode($arr);

   	exit();

  }

  function send_success($text) {

    // set response code
    http_response_code(200);

    // set type
   	header('Content-Type: text/html');

   	// set text if provided
   	print $text;

   	exit();

  }

  function validate_token() {

    $auth = $_SERVER['HTTP_X_AUTH'];

    $token = NULL;

    if($auth != NULL) {
      $token = App\Respond\Libraries\Utilities::ValidateJWTToken($auth);

      if($token != NULL) {

        return array(
          'email' => $token->email,
          'id' => $token->id);

      }
      else {
        send_response(401, 'You\'re session has expired. Please login again.');
      }

    }
    else {
      send_response(401, 'You\'re session has expired. Please login again.');
    }


  }

?>