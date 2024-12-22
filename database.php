<?php
    $server = '127.0.0.1';
    $port = 3306;
    $dbname = 'laptop_database';
    $user = 'app';
    $password = '1234';
    
    try{
        $pdo = new PDO("mysql:host=$server;port=$port;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        die("Error connecting to database. ". $e->getMessage());
    }
?>