<?php
/*
 * Configuration settings
 */

    // used by all api services files
  
    // show error reporting
    error_reporting(E_ALL);
     
    // set default time-zone
    date_default_timezone_set('Asia/Jakarta');
    
    define('SALT', '70edfg46df63t54rt6g56g128522ebd34a7f1a04dsdf425b4g73h327514ac8');
     
    // variables used for jwt
    $key = "cvbrb84gd38x3b3f73578gf3cbnxjffhfgv7"; // candidate bearer variable used for auth
    $issuedAt = time();
    $expirationTime = $issuedAt + 3600; // 1 hour expire time
    $iss = "http://example.org";
    $aud = "http://example.com";
    $iat = $issuedAt;
    $nbf = 1357000000;
    $exp = $expirationTime;

    // declare a function to fetch headers. because the native php function is not recognized by nginx server environment
    if (!function_exists('getallheaders'))
    {
        function getallheaders()
        {
           $headers = array ();
           foreach ($_SERVER as $name => $value)
           {
               if (substr($name, 0, 5) == 'HTTP_')
               {
                   $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
               }
           }
           return $headers;
        }
    }


?>
