<aside class="aside">
    <?php
        if($loggedIn){
            $sideLinks = [
                [
                    'url' => 'index.php',
                    'title' => 'Home',
                    'icon'=> 'home',
                ],
                
                [
                    'url' => 'add.php',
                    'title' => 'Add Laptops',
                    'icon'=> 'add',
                ],
        
            ];
        }
    ?>
    <div class="links">
        <div class="site_name">
            <span class="material-symbols-outlined">laptop_windows</span>
            <span>LAPTOP DB</span>
        </div>

        <?php
            if(isset($sideLinks)):
                foreach($sideLinks as $link):
        ?>            
            <a href="<?= $link['url'] ?>" <?= basename($_SERVER['REQUEST_URI']) == $link['url'] ? 'class="current"' : ''  ?>>
                <span class="material-symbols-outlined"><?= $link['icon'] ?></span>
                <span><?= $link['title'] ?></span>
            </a>

            <?php endforeach; ?>
            
        <?php endif; ?>
    </div>

    <?php if(isset($sideLinks)): ?>
    <div class="user">
        <span class="username"><?= $_SESSION['username'] ?></span>
        <a href="logout.php">
            <span class="material-symbols-outlined">logout</span>
            <span>Log out</span>
        </a>
    </div>
    <?php endif; ?>
</aside>