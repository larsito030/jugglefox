<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include("functions.php"); 
include("config.php");
if(isset($_POST['password'])) {
	$password = md5($_POST['password']);
	$username = $_POST['username'];
        $active = "SELECT active FROM login WHERE username = '$username' AND password = '$password'";
        $data = $db->prepare($active);
        $data->execute();
        $query = "SELECT id FROM login WHERE username = '$username' AND password = '$password'";
        $data = $db->prepare($query);
        $data->execute();
        $count = $data->rowCount();
        if($count === 1) {
            $query2 = "SELECT id FROM login WHERE username = '$username' AND password = '$password' AND active = 1";
            $data2 = $db->prepare($query2);
            $data2->execute();
            $count2 = $data2->rowCount();     
            if($count2 === 1) {
                $_SESSION['username'] = $username;
            } else{
                echo "Please activate first your account!";
            }
            
        } else {
            echo "username or password not correct!";
        }
}
//login_user($db);
