<?php
require 'database.php';

if (
    isset($_POST['id'])
    && isset($_POST['save'])
) {

    $id = $_POST['id'];
    $mfr = $_POST['manufacturer'];
    $mdl = $_POST['model'];
    $scr = $_POST['screen'];
    $cpu = $_POST['cpu'];
    $gpu = $_POST['gpu'];
    $ram = $_POST['ram'];
    $str = $_POST['storage'];
    $rld = $_POST['release_date'];
    $prc = $_POST['price'];

    $sql = 'UPDATE laptops SET manufacturer=:mfr, model=:mdl, screen=:scr, cpu=:cpu, gpu=:gpu, ram=:ram, storage=:str, release_date=:rld, price=:prc, image_url=:img WHERE id=:id';

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
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(':img', $image_url);

        $stmt->execute();

        header("Location: index.php");
    } catch (PDOException $e) {
        die("Error " . $e->getMessage());
    }
}
