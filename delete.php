<?php
require 'database.php';
if(isset($_POST['id'])){
    $id = $_POST['id'];

    include 'database.php';

    try{
        $sql = "DELETE FROM laptops WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        header("Location: index.php");

    }catch(PDOException $e){
        die("Error deleting record: " . $e->getMessage());
    }

}else{
    die("Error 403: Page not found.");
}