<?php

    require "../config/dbConfig.php";

    // set header
    header('Content-Type: application/json');
    $header = getallheaders();

    registration();
    
    function registration() {

        global $conn;

        $today = date('d/m/Y');
        
        // get posted data
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        
        // set property values
        $FirstName = isset($data->FirstName) ? $data->FirstName : '';
        $LastName = isset($data->LastName) ? $data->LastName : '';
        $Email = isset($data->Email) ? $data->Email : '';
        $PhoneNumber = isset($data->PhoneNumber) ? $data->PhoneNumber : '';
        $Gender = isset($data->Gender) ? $data->Gender : '';
        $BirthDate = isset($data->BirthDate) ? $data->BirthDate : '';
        $Password = isset($data->Password) ? $data->Password : '';
        $ConfirmPassword = isset($data->ConfirmPassword) ? $data->ConfirmPassword : '';

        // BEGIN TRANSACTION //
        $conn->autocommit(FALSE);
        
        // VALIDATION
        // check empty FirstName
        if (empty($FirstName) || $FirstName == "") {
            $response = array(
                'status' => 0,
                'message' => 'First Name is required'
            );

            http_response_code(200);
            echo json_encode($response);
            die();
        }

        // check empty LastName
        if (empty($LastName) || $LastName == "") {
            $response = array(
                'status' => 0,
                'message' => 'Last Name is required'
            );

            http_response_code(200);
            echo json_encode($response);
            die();
        }

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

        // find email on database
        $findemail = $conn->query("SELECT COUNT(*) as jml_email FROM users WHERE email = '".$Email."'");
        $email = $findemail->fetch_assoc();
        
        // check Unique Email
        if ($email['jml_email'] != 0) {
            $response = array(
                'status' => 0,
                'message' => 'Email must be unique'
            );

            http_response_code(200);
            echo json_encode($response);
            die();
        }

        // check valid email
        if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
            $response = array(
                'status' => 0,
                'message' => 'Email is invalid'
            );

            http_response_code(200);
            echo json_encode($response);
            die();
        }

        // check empty PhoneNumber
        if (empty($PhoneNumber) || $PhoneNumber == "") {
            $response = array(
                'status' => 0,
                'message' => 'Phone Number is required'
            );

            http_response_code(200);
            echo json_encode($response);
            die();
        }

        // find phone number on database
        $findphone = $conn->query("SELECT COUNT(*) as jml_phone FROM users WHERE phone = '".$PhoneNumber."'");
        $phonenumber = $findphone->fetch_assoc();
        
        // check Unique PhoneNumber
        if ($phonenumber['jml_phone'] != 0) {
            $response = array(
                'status' => 0,
                'message' => 'Phone Number must be unique'
            );

            http_response_code(200);
            echo json_encode($response);
            die();
        }

        // check enum Gender
        if ($Gender != 'M' AND $Gender != 'F') {
            $response = array(
                'status' => 0,
                'message' => 'Gender is invalid format (M / F)'
            );

            http_response_code(200);
            echo json_encode($response);
            die();
        }

        // check valid BirthDate
        if (!preg_match('/^([0-9]{2})\\/([0-9]{2})\\/([0-9]{4})$/', $BirthDate)) {
            $response = array(
                'status' => 0,
                'message' => 'Birth Date is invalid format (dd/mm/yyyy)'
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

        // check empty Confirm Password
        if (empty($ConfirmPassword) || $ConfirmPassword == "") {
            $response = array(
                'status' => 0,
                'message' => 'Confirm Password is required'
            );

            http_response_code(200);
            echo json_encode($response);
            die();
        }

        // check Password and Confirm Password match
        if ($ConfirmPassword !== $Password) {
            $response = array(
                'status' => 0,
                'message' => 'Confirm Password is not match with password'
            );

            http_response_code(200);
            echo json_encode($response);
            die();
        }

        // hash password
        $options = array(
            'cost' => 10
        );

        // hash with BCRYPT
        $hashed_password = password_hash($ConfirmPassword, PASSWORD_DEFAULT, $options);


        // ======= INSERT DATA ======= //

        //set uuid
        $set_uuid = "set @idUser = UUID()";
        $result_set_uuid_book = $conn->query($set_uuid);

        $data = array(
            'first_name' => $FirstName,
            'last_name' => $LastName,
            'email' => $Email,
            'password' => $hashed_password,
            'birthdate' => $BirthDate,
            'gender' => $Gender,
            'phone' => $PhoneNumber,
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s')
        );

        $column = implode(", ", array_keys($data));
        $escaped_values = array_map(array($conn, 'real_escape_string'), array_values($data));
        $values  = "'".implode("', '", $escaped_values)."'";
        
        // insert to db
        $AddUser = $conn->query("INSERT INTO users (id, $column) VALUES (@idUser, $values)");
        

        if ($AddUser) {
            $conn->commit();

            $response = array(
                'status' => 1,
                'message' => 'Data saved success'
            );
                            
            http_response_code(200);
            echo json_encode($response);
        } else{
            $conn->rollback();

            $response = array(
                'status' => 0,
                'message' => 'Data saved failed'
            );
                            
            http_response_code(200);
            echo json_encode($response);
        }

    }


?>