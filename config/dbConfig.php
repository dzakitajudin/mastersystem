<?php

    // setting db lokal
    // $host = '127.0.0.1';
    $host = 'mysql'; // container name database env in docker
    $login = 'root';
    $pass = 'pass';
    $db = 'mastersystem';
    $port = '3306';

    // create connection
    $conn = new mysqli($host,$login,$pass,$db,$port);

    if ($conn->connect_errno) {
        // show error
        printf("Connect failed: %s\n", $conn->connect_error);
        exit();
    }

?>