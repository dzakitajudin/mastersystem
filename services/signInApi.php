<?php

    require "../config/dbConfig.php";
    // generate json web token
    require "../libs/BeforeValidException.php";
    require "../libs/ExpiredException.php";
    require "../libs/SignatureInvalidException.php";
    require "../libs/JWT.php";

    use \Firebase\JWT\JWT;

    // set header
    header('Content-Type: application/json');
    $header = getallheaders();

    login();

    function login() {

        global $conn;

        // get posted data
        $json = file_get_contents('php://input');
        $data = json_decode($json);


        // set property values
        $Email = isset($data->Email) ? $data->Email : '';
        $Password = isset($data->Password) ? $data->Password : '';
        

        // VALIDATION
        // check empty Email
        if (empty($Email) || $Email == "") {
            $response = array(
                'status' => 0,
                'message' => 'Email is required'
            );

            http_response_code(200);
            echo json_encode($response);
            die();
        }

        // check empty Password
        if (empty($Password) || $Password == "") {
            $response = array(
                'status' => 0,
                'message' => 'Password is required'
            );

            http_response_code(200);
            echo json_encode($response);
            die();
        }

        // query sql to get data users
        $sql = $conn->query("SELECT * FROM users WHERE email = '".$Email."' LIMIT 1");
        $user = $sql->fetch_assoc();
        
        // check valid password
        if (password_verify($Password, $user['password'])) {
            
            // if correct, then return given jwt and display message success

            $key = "cvbrb84gd38x3b3f73578gf3cbnxjffhfgv7";
                
            $alg = 'HS256';

            $issuedAt = time();
            $expirationTime = $issuedAt + 3600; // jwt valid for 60 minutes from the issued time

            $token = array(
                "email" => $Email,
                "iat" => $issuedAt,
                "exp" => $expirationTime
            );


            // generate jwt
            $jwt = JWT::encode($token, $key, $alg);


            $arr_token = array(
                'token' => $jwt,
                'iat' => $issuedAt,
                'expire' => $expirationTime
            );
            $obj_token = $arr_token;

            $response = array(
                'status' => 1,
                'message' => 'Login success',
                'data' => array($obj_token)
            );

            http_response_code(200);

            echo json_encode($response);
        } else {
            
            // if doesn't exist in db
            $response = array(
                'status' => 0,
                'message' => "email and password doesn't match"
            );

            http_response_code(200);

            echo json_encode($response);
        }

    }



?>