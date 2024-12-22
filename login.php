<?php 
    $pageTitle = 'Laptop DB | Login';
    include '_header.php';
?>

<body>
    <?php
        $loggedIn = false;
        include '_aside.php';
    ?>
    <?php 
        $errors = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            require_once 'database.php';

            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            if(empty($username)){
                $errors['empty_user'] = "Username field should not be empty";
            } elseif (empty($password)) {
                $errors['empty_pass'] = "Password field should not be empty";
            } else {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$username]);
                $user = $stmt->fetch();

                if($user && password_verify($password, $user['user_password'])){
                    session_start();
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['last_activity'] = time();

                    session_regenerate_id(true);

                    header("Location:index.php");
                } else {
                    $errors['lgn_fail'] = "Incorrect user or password";
                }
            }
        }
    ?>
    
    <main>
        <h2>Login Account</h2>

        <?php if(!empty($errors['empty_user'])): ?>
            <p class="error_text"><?= $errors['empty_user'] ?></p>
        <?php endif; ?>
        <?php if(!empty($errors['empty_pass'])): ?>
            <p class="error_text"><?= $errors['empty_pass'] ?></p>
        <?php endif; ?>
        <?php if(!empty($errors['lgn_fail'])): ?>
            <p class="error_text"><?= $errors['lgn_fail'] ?></p>
        <?php endif; ?>

        <form method="POST" class="add_edit_form">
            <div>
                <label for="username">Username</label>
                <input type="text" name="username" id="username" 
                <?php 
                if(isset($errors['lgn_fail']) || isset($errors['empty_user'])){
                    echo "class='error'";
                }
                ?>>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password"
                <?php 
                if(isset($errors['lgn_fail']) || isset($errors['empty_pass'])){
                    echo "class='error'";
                }
                ?>>
            </div>

            <button type="submit">Login</button>

            <div>
                <p><a href="register.php">Register now!</a></p>
            </div>
        </form>
    </main>
</body>

<?php include '_footer.php'; ?>