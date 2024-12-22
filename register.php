<?php 
    $pageTitle = 'Laptop DB | Register';
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
            require 'database.php';

            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $repassword = trim($_POST['repassword']);

            if(empty($username) || empty($email) || empty($password) || empty($repassword)){
                $errors['empty_field'] = "Please fill out all the fields.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email_err'] = "Invalid email format";
            } elseif ($password != $repassword){
                $errors['repass_err'] = "Password missmatched";
            } else {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
                $stmt->execute([$username, $email]);
                if($stmt->rowCount() > 0){
                    $errors['user_exists'] = "User already exists";
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = $pdo->prepare("INSERT INTO users(username, email, user_password) VALUES (?, ?, ?)");
                    if ($stmt->execute([$username, $email, $hashedPassword])) {
                        header("Location: login.php");
                    } else {
                        $errors['reg_err'] = "Registration failed. Please try again.";
                    }
                }
            }
        }
    ?>
    
    <main>
        <h2>Register Account</h2>
        <?php if(!empty($errors['empty_field'])): ?>
            <p class="error_text"><?= $errors['empty_feild'] ?></p>
        <?php endif; ?>
        <?php if(!empty($errors['user_exists'])): ?>
            <p class="error_text"><?= $errors['user_exists'] ?></p>
        <?php endif; ?>
        <?php if(!empty($errors['reg_err'])): ?>
            <p class="error_text"><?= $errors['reg_err'] ?></p>
        <?php endif; ?>

        <form method="POST" class="add_edit_form">
            <div>
                <label for="username">Username</label>
                <input type="text" name="username" id="username" 
                <?php 
                if(isset($errors['user_exists']) || isset($errors['empty_field'])){
                    echo "class='error'";
                }
                ?>>
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" 
                <?php 
                if(isset($errors['email_err']) || isset($errors['empty_field'])){
                    echo "class='error'";
                }
                ?>>
            </div>
            <?php if(!empty($errors['email_err'])): ?>
                <p class="error_text"><?= $errors['email_err'] ?></p>
            <?php endif; ?>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" 
                <?php 
                if(isset($errors['repass_err']) || isset($errors['empty_field'])){
                    echo "class='error'";
                }
                ?>>
            </div>
            <div>
                <label for="repassword">Confirm Password</label>
                <input type="password" name="repassword" id="repassword"  
                <?php 
                if(isset($errors['repass_err']) || isset($errors['empty_field'])){
                    echo "class='error'";
                }
                ?>>
            </div>
            <?php if(!empty($errors['repass_err'])): ?>
                <p class="error_text"><?= $errors['repass_err'] ?></p>
            <?php endif; ?>

            <button type="submit">Register</button>

            <div>
                <p>Already registered? <a href="login.php">Login!</a></p>
            </div>
        </form>
    </main>
</body>

<?php include '_footer.php'; ?>