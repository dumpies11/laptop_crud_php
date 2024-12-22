<?php
session_start();

if ((time() - $_SESSION['last_activity']) > 300) {
    session_unset();
    session_destroy();

    header("Location:login.php");
}

$_SESSION['last_activity'] = time();

if (!isset($_SESSION['user_id'])) {
    header("Location:login.php");
    exit;
}

$pageTitle = 'Laptop DB | Edit Laptop';
include '_header.php';
require 'database.php';
?>

<body>
    <?php
    $loggedIn = true;
    include '_aside.php';
    ?>
    <?php
    if (isset($_POST['id'])) {

        $id = $_POST['id'];

        try {
            $sql = 'SELECT * FROM laptops WHERE id = :id';

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $laptop = $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die("Error fetching data: " . $e->getMessage());
        }
    }
    ?>

    <main>
        <div class="menu">
            <div class="hamburger">
                <span class="material-symbols-outlined">menu</span>
            </div>
            <div class="close">
                <span class="material-symbols-outlined">close</span>
            </div>
        </div>

        <h1>Edit Laptop</h1>

        <?php
        $errors = [];
        if (
            isset($_POST['manufacturer']) &&
            isset($_POST['model']) &&
            isset($_POST['release_date']) &&
            isset($_POST['price'])
        ) {
            $manufacturer = $_POST['manufacturer'];
            $model = $_POST['model'];
            $release = $_POST['release_date'];
            $price = $_POST['price'];

            if (empty(trim($manufacturer))) {
                $errors['manufacturer'] = 'Laptop Manufacturer is required';
            }
            if (empty(trim($model))) {
                $errors['model'] = 'Laptop model is required';
            }
            if (empty(trim($release))) {
                $errors['release_date'] = 'Laptop release date is required';
            }
            if (empty(trim($price))) {
                $errors['price'] = 'Laptop price is required';
            }

            if (!empty(trim($price)) && !filter_var($price, FILTER_VALIDATE_FLOAT)) {
                $errors['price'] = 'Laptop price should be a number';
            }

            $upload_dir = "images";
            $file = $_FILES['laptop_image'];
            $type = mime_content_type($file['tmp_name']);

            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $type = mime_content_type($file['tmp_name']);
                if (str_starts_with($type, 'image')) {
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $new_file_name = uniqid('img_') . ".$ext";
                    $destination = $upload_dir . DIRECTORY_SEPARATOR . $new_file_name;

                    if (move_uploaded_file($file['tmp_name'], $destination)) {
                        $image_url = $new_file_name;
                    } else {
                        $errors['image'] = 'Failed to upload image.';
                    }
                } else {
                    $errors['image'] = 'Only image files are allowed.';
                }
            }

            if (count($errors) == 0) {
                include 'process_edit.php';
            }
        }
        ?>

        <form method="post" class="add_edit_form" enctype="multipart/form-data">
            <div>
                <label for="manufacturer">Manufacturer</label>
                <input type="text" id="manufacturer" name="manufacturer" value="<?= $laptop->manufacturer ?>"
                    <?php
                    if (isset($errors['manufacturer']))
                        echo "class='error'";
                    ?>>
            </div>
            <?php
            if (isset($errors['manufacturer']))
                echo "<p class='error_text'>" . $errors['manufacturer'] . "</p>";
            ?>

            <div>
                <label for="model">Model</label>
                <input type="text" id="model" name="model" value="<?= $laptop->model ?>"
                    <?php
                    if (isset($errors['model']))
                        echo "class='error'";
                    ?>>
            </div>
            <?php
            if (isset($errors['model']))
                echo "<p class='error_text'>" . $errors['model'] . "</p>";
            ?>

            <div>
                <label for="screen">Screen</label>
                <input type="text" id="screen" name="screen" value="<?= $laptop->screen ?>">
            </div>

            <div>
                <label for="cpu">CPU</label>
                <input type="text" id="cpu" name="cpu" value="<?= $laptop->cpu ?>">
            </div>

            <div>
                <label for="gpu">GPU</label>
                <input type="text" id="gpu" name="gpu" value="<?= $laptop->gpu ?>">
            </div>

            <div>
                <label for="ram">Ram</label>
                <input type="text" id="ram" name="ram" value="<?= $laptop->ram ?>">
            </div>

            <div>
                <label for="storage">Storage</label>
                <input type="text" id="storage" name="storage" value="<?= $laptop->storage ?>">
            </div>

            <div>
                <label for="release_date">Release Date</label>
                <input type="date" id="release_date" name="release_date" value="<?= $laptop->release_date ?>"
                    <?php
                    if (isset($errors['release_date']))
                        echo "class='error'";
                    ?>>
            </div>
            <?php
            if (isset($errors['release_date']))
                echo "<p class='error_text'>" . $errors['releas_date'] . "</p>";
            ?>

            <div>
                <label for="price">Price</label>
                <input type="number" id="price" name="price" value="<?= $laptop->price ?>"
                    <?php
                    if (isset($errors['price'])) {
                        echo "class='error'";
                    }
                    ?>>
            </div>
            <?php
            if (isset($errors['price'])) {
                echo "<p class='error_text'>" . $errors['price'] . "</p>";
            }
            ?>

            <div>
                <label for="laptop_image">Laptop Image</label>
                <input type="file" id="laptop_image" name="laptop_image">
            </div>

            <input type="hidden" name="id" value="<?= $laptop->id ?>">
            <button type="submit" name="save" onclick="confirmEdit(event)">Save</button>
        </form>
    </main>
</body>

<?php include '_footer.php'; ?>