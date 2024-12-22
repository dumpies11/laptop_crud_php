<?php
require 'database.php';

if (
    isset($_POST['manufacturer'])
    && isset($_POST['model'])
    && isset($_POST['screen'])
    && isset($_POST['cpu'])
    && isset($_POST['gpu'])
    && isset($_POST['ram'])
    && isset($_POST['storage'])
    && isset($_POST['release_date'])
    && isset($_POST['price'])
    && isset($_FILES['laptop_image'])
) {

    $mfr = $_POST['manufacturer'];
    $mdl = $_POST['model'];
    $scr = $_POST['screen'];
    $cpu = $_POST['cpu'];
    $gpu = $_POST['gpu'];
    $ram = $_POST['ram'];
    $str = $_POST['storage'];
    $rld = $_POST['release_date'];
    $prc = $_POST['price'];

    $sql = 'INSERT INTO laptops(manufacturer, model, screen, cpu, gpu, ram, storage, release_date, price, image_url) values(:mfr, :mdl, :scr, :cpu, :gpu, :ram, :str, :rld, :prc, :img)';

    try {
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":mfr", $mfr);
        $stmt->bindParam(":mdl", $mdl);
        $stmt->bindParam(":scr", $scr);
        $stmt->bindParam(":cpu", $cpu);
        $stmt->bindParam(":gpu", $gpu);
        $stmt->bindParam(":ram", $ram);
        $stmt->bindParam(":str", $str);
        $stmt->bindParam(":rld", $rld);
        $stmt->bindParam(":prc", $prc);
        $stmt->bindParam(':img', $image_url);


        if ($stmt->execute()) {
            $_SESSION['success'] = "Laptop Added Successfully.";
        } else {
            $_SESSION['fail'] = "Laptop failed to be added.";
        }

        header("Location:index.php");
    } catch (PDOException $e) {
        die("Error " . $e->getMessage());
    }
}
