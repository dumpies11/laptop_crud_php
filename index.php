<?php
session_start();

if ((time() - $_SESSION['last_activity']) > 1800) {
    session_unset();
    session_destroy();

    header("Location:login.php");
}

$_SESSION['last_activity'] = time();

if (!isset($_SESSION['user_id'])) {
    header("Location:login.php");
    exit;
}

$pageTitle = 'Laptop DB | Home';
include '_header.php';
require 'database.php';
?>

<body>

    <?php
    $loggedIn = true;
    include '_aside.php';
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

        <form class="search_form">
            <input type="text" name="search" placeholder="Search...">
            <button>
                <span class="material-symbols-outlined">search</span>
            </button>
        </form>

        <?php
        try {
            $sql = 'SELECT * from laptops';

            $stmt = $pdo->query($sql);

            $laptops = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (isset($_GET['search'])) {
                $sql .= " WHERE manufacturer LIKE :search OR model LIKE :search OR screen LIKE :search OR cpu LIKE :search OR gpu LIKE :search OR ram LIKE :search OR storage LIKE :search";

                $prepared_statement = $pdo->prepare($sql);

                $search = htmlspecialchars($_GET['search']);
                $search = "%$search%";
                $prepared_statement->bindParam(':search', $search);
                $prepared_statement->execute();

                $laptops = $prepared_statement->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            die("Error fetching data: " . $e->getMessage());
        }
        ?>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Manufacturer</th>
                    <th>Model</th>
                    <th>Screen</th>
                    <th>CPU</th>
                    <th>GPU</th>
                    <th>Ram</th>
                    <th>Storage</th>
                    <th>Release Date</th>
                    <th>Price</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($laptops as $laptop): ?>
                    <tr>
                        <td>
                            <?php if (!empty($laptop['image_url'])): ?>
                                <img src="images/<?= $laptop['image_url'] ?>" alt="Laptop Image" style="width: 100px; height: auto;">
                            <?php else: ?>
                                <span>No Image</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $laptop['manufacturer'] ?></td>
                        <td><?= $laptop['model'] ?></td>
                        <td><?= $laptop['screen'] ?></td>
                        <td><?= $laptop['cpu'] ?></td>
                        <td><?= $laptop['gpu'] ?></td>
                        <td><?= $laptop['ram'] ?></td>
                        <td><?= $laptop['storage'] ?></td>
                        <td><?= $laptop['release_date'] ?></td>
                        <td>PHP <?= $laptop['price'] ?></td>
                        <td class='action'>

                            <form on method="post" action="delete.php">
                                <input type="hidden" name="id" value="<?= $laptop['id'] ?>">
                                <button onclick="confirmDelete(event)">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </form>

                            <form on method="post" action="edit.php">
                                <input type="hidden" name="id" value="<?= $laptop['id'] ?>">
                                <button>
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                            </form>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

</body>


<?php include '_footer.php'; ?>