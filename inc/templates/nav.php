<div>
<div class="float-left">
<p>
    <a href="index.php?task=report">All Students</a> | 
    <a href="index.php?task=add">Add New Students</a> | 
    <a href="index.php?task=seed">Seed</a>
</p>
</div>
<div class="float-right">
    <?php if($_SESSION['loggedin'] == false):?>
        <a href="auth.php">Login</a>
    <?php else:?>
        <a href="auth.php?logout=true">Log out (<?php echo $_SESSION['role']?>)</a>
    <?php endif;?>
</div>
</div>