<?php 
session_name('auth');
session_start(array(
    'cookie_lifetime'=>300,
));
// var_dump($_SESSION['loggedin']);
$_SESSION['loggedin'] = $_SESSION['loggedin']??false;
//file path
$fp = fopen('./data/user.txt', "r");
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

$error = false;
//check form 
if(isset($username) && isset($password)){
    while($data = fgetcsv($fp)){
        if($data[0] == $username && $data[1] == sha1($password)){
            $_SESSION['loggedin'] = true; 
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $data['2'];
            header('location: index.php');
        }else{
            $_SESSION['loggedin'] = false; 
            $error = true;
        }
    }
}

if(isset($_GET['logout']) && 'true' == $_GET['logout']){
    $_SESSION['loggedin'] = false;
    session_destroy();
    header('location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <title>Simple Authentication</title>
    <style>
        body {
            margin-top: 30px;
        }
        #data, #result{
            width: 100%;
            height: 160px;
        }
    </style>
</head>
<body>
<div class="container">   
        <div class="row">
            <div class="column column-50 column-offset-25">
            <div>
                <h2>Authentication</h2>
                
                <?php 
                    if($error){
                        echo "<blockquote>Username and Password Not Match</blockquote>";
                    }
                ?>
                <?php 
                    if(true == $_SESSION['loggedin']){
                        echo "<p>Hello Admin, Welcome</p>";
                    }else{
                        echo "<p>Hello Please Logged in</p>";
                    }
                ?>
                
            </div>
            <?php if(false == $_SESSION['loggedin']):?>
                <form method="POST" >
                    <fieldset>
                        <label for="key">User Name</label>
                        <input type="text" name="username" id="username">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password">
                        <input type="submit" class="button-primary" value="Login">
                    </fieldset>
                </form>
            <?php 
                endif;    
            ?>
            </div>
            
        </div>
    </div>
</body>
</html>