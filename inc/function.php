<?php

// set a data file/DB
define("DB_NAME", "e:\\php-7-course\\practice\\crud\\data\\db.txt");

//define data

//data seeding
function seed(){
    $students = array(
        array(
            "id" =>  1,
            "fname" =>  "Imran",
            "lname" =>  "Khan",
            "roll" => 9
        ),
        array(
            "id" =>  2,
            "fname" =>  "Emon",
            "lname" =>  "Khan",
            "roll" => 12
        ),
        array(
            "id" =>  3,
            "fname" =>  "Mohammad",
            "lname" =>  "Ali",
            "roll" => 11
        ),
        array(
            "id" =>  4,
            "fname" =>  "Ariful",
            "lname" =>  "Islam",
            "roll" => 13
        ),
        array(
            "id" =>  5,
            "fname" =>  "Rabiul",
            "lname" =>  "Tanmoy",
            "roll" => 14
        ),
    );
    $data = serialize($students);
    file_put_contents(DB_NAME, $data);
}

//show all data
function generateReport(){
    $data = file_get_contents(DB_NAME);
    return unserialize($data);
}

//add student
function addStudent($fname, $lname, $roll){
    $data = file_get_contents(DB_NAME);
    $students = unserialize($data);
    $found = false;
    foreach($students as $student){
        if($student['roll'] == $roll){
            $found = true;
            break;
        }
    }
    
    if(!$found){
        $id = max(array_column($students, 'id')) + 1;
        $newStudent = array(
        "id" =>  $id,
        "fname" =>  $fname,
        "lname" =>  $lname,
        "roll" => $roll
        );
        array_push($students, $newStudent);
        $serializedData = serialize($students);
        file_put_contents(DB_NAME, $serializedData, LOCK_EX);
        return true;
        }else{
            return false;
    }
    
}

function getStudent($id){
    $data = file_get_contents(DB_NAME);
    $students = unserialize($data);
    foreach($students as $student){
        if($id == $student['id']){
            return $student;
        }
    }
    return false;
}


function updateStudents($id, $fname, $lname, $roll){
    $data = file_get_contents(DB_NAME);
    $students = unserialize($data);

    $found = false;
    foreach($students as $student){
    if($student['roll'] == $roll && $student['id'] != $id){
        $found = true;
        break;
        }
    }

    if(!$found){
    //updated student using map function and based on student id
    $updateStudents = array_map(function($student) use ($id, $fname, $lname, $roll){
        
        if($student['id'] == $id){
            //return a new array based on 'id'
            return array(
                'id' => $student['id'],
                'fname' => $fname,
                'lname' => $lname,
                'roll' => $roll
        );
        }else{
           return $student;
        }
        
    },$students);

    // var_dump($updateStudents);

    $serializedData = serialize($updateStudents);
    file_put_contents(DB_NAME, $serializedData, LOCK_EX);
    return true;
    }else{
        return false;
    }

}

//delete
function deleteStudent($id){
    $data = file_get_contents(DB_NAME); /* get data from file */
    $students = unserialize($data); /* unserialize the data for process */

    $updateStudents = array_filter($students, function($student) use($id){
        if($student['id'] != $id){
            return $student;
        };
    });
    $serializedData = serialize($updateStudents);
    file_put_contents(DB_NAME, $serializedData, LOCK_EX);

}

//isAdmin
function isAdmin(){
    return "admin" == $_SESSION['role'];
};

function isEditor(){
    return "editor" == $_SESSION['role'];
};

function isPrivilege(){
    return ("admin" == $_SESSION['role'] || "editor" == $_SESSION['role']);
};