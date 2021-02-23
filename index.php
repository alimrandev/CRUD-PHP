<?php
session_name('auth');
session_start();
require_once "./inc/function.php";
$_SESSION['loggedin'] = $_SESSION['loggedin']??false;
var_dump($_SESSION['loggedin']);
    $info = '';
    $task = $_GET['task'] ?? 'report';
    $error = $_GET['error'] ?? '0';
    if('seed' == $task) {
        seed();
        $info = 'Data Seeding Completed';
    }
    //delete
    if('delete' == $task) {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
        deleteStudent($id);
        header("location: index.php?task=report");
    }
    //let define inputs
    $fname  = '';
    $lname = '';
    $roll = '';
    if(isset($_POST['submit'])){
        $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
        $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
        $roll = filter_input(INPUT_POST, 'roll', FILTER_SANITIZE_STRING);

        if($fname != '' && $lname != '' && $roll != ''){
            $result = addStudent($fname, $lname, $roll);
            if($result){
            header("location: index.php?task=report");
            }else{
                $error  = 1; 
            }
        }
        
    }

    //update

    if(isset($_POST['update'])){
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
        $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
        $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
        $roll = filter_input(INPUT_POST, 'roll', FILTER_SANITIZE_STRING);
        $result = updateStudents($id, $fname, $lname, $roll);
        if($result){
            header("location: index.php?task=report");
            }else{
                $error  = 1; 
            }
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
    <title>Simple Form</title>
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
                <h2>Student Database | CRUD App</h2>
                <p>A simple CRUD App using PHP</p>
            </div>
            <div>
                <?php include_once "inc/templates/nav.php"?>
                <p><?php if($info != '') {echo $info;}?></p>
            </div>
            </div>
            </div>
            <?php if('1' == $error):?>
                <div class="row">
                    <div class="column column-50 column-offset-25">
                        <blockquote>Duplicate Roll, Student Not Added</blockquote>
                    </div>
                </div>
            <?php endif?>
            <?php if('add' == $task): ?>
                <div class="row">
                    <div class="column column-50 column-offset-25">
                        <form action="index.php?task=add" method="POST">
                            <label for="fname">First Name</label>
                            <input type="text" name="fname" id="fname" value="<?php echo $fname?>">
                            <label for="lname">First Name</label>
                            <input type="text" name="lname" id="lname" value="<?php echo $lname?>">
                            <label for="roll">Roll</label>
                            <input type="number" name="roll" id="roll" value="<?php echo $roll?>">
                            <button type="submit" name="submit">Save</button>
                        </form>
                    </div>
                </div>
            <?php endif;?>
            <!-- Showing Report -->
            <?php if('report' == $task): ?>
                <div class="row">
                    <div class="column column-50 column-offset-25">
                    <?php $students = generateReport();?>  
                <table>
                    <tr>
                        <td>Name</td>
                        <td>Roll</td>
                        <td>Action</td>
                    </tr>
                    <?php foreach($students as $student):?>
                        <tr>
                            <td><?php printf('%s %s', $student['fname'], $student['lname'])?></td>
                            <td><?php printf('%s', $student['roll']) ?></td>
                            <td><a href="index.php?task=edit&id=<?php echo $student['id']?>">Edit</a> | <a href="index.php?task=delete&id=<?php echo $student['id']?>" class="confirm">Delete</a></td>
                        </tr>
                    <?php endforeach?>
                </table>
                    
                    </div>
                </div>
            <?php endif;?>
        <!-- Edit Student -->
            <?php 
                if('edit' == $task): 
                    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
                    $student = getStudent($id);
                if($student):
            ?>
                <div class="row">
                    <div class="column column-50 column-offset-25">
                        <form action="index.php?task=edit" method="POST">
                            <input type="text" name="id" hidden value="<?php echo $id?>">
                            <label for="fname">First Name</label>
                            <input type="text" name="fname" id="fname" value="<?php echo $student['fname']?>">
                            <label for="lname">First Name</label>
                            <input type="text" name="lname" id="lname" value="<?php echo $student['lname']?>">
                            <label for="roll">Roll</label>
                            <input type="number" name="roll" id="roll" value="<?php echo $student['roll']?>">
                            <button type="submit" name="update">Update</button>
                        </form>
                    </div>
                </div>
            <?php 
            endif;
            endif;
            ?>
    </div>

    <script src="./assets/script.js"></script>
</body>
</html>