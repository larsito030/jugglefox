
<?php

function sanitize($data) {
    return htmlentities(mysqli_real_escape_string($data));
}


function update_highscore($db, $score) {
        $username = $_SESSION['username'];
        $query = "UPDATE highscores AS h INNER JOIN login ";
        $query .= "AS l ON l.id = h.user_id SET h.highscore = '$score' ";
        $query .= "WHERE l.username = '$username'";
        $data = $db->prepare($query);
        $data->execute();
        }

function login_user($db) {
    if(!empty($_POST['username'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $query = "SELECT id FROM login WHERE username = '$username' AND password = '$password'";
        $data = $db->prepare($query);
        $data->execute();
        $count = $data->rowCount();
        if($count === 1) {
            $_SESSION['username'] = $username;
            console.log('Login successful');
        } else {
            console.log('Login not successful');
        }
    }
}

function user_data($db, $field, $identifier, $value) {
    $query = "SELECT $field FROM login WHERE $identifier = '$value'";
    $data = $db->prepare($query);
    $data->execute();
    $result = $data->fetch();
    $out = $result[0];
    return $out;
}

function activation_mail($db) {
    $email = $_POST['email'];
    $email_code = user_data($db, 'email_code', 'email', $email);
    $message  = "<html><body>";
    $message .= "Dear ".$_POST['username'].", <br><br>";
    $message .= "please click the link below in order to activate your account:<br>";
    $message .= "http://larsitogames.com/jugglefox/jugglefox.php?email=".$email."&email_code=".$email_code."<br><br>";
    $message .= "Lars from larsitogames.com";
    $message .= "</body></html>";
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    mail($email, 'Your Jugglefox account', $message, $headers);
}

function activate() {
    global $db;
    $email = $_GET['email'];
    $email_code = $_GET['email_code'];
    if(isset($_GET['email'])) {
        $query = "SELECT id FROM login WHERE email = '$email' ";
        $query .= "AND email_code = '$email_code' AND active = 0";
        $data = $db->prepare($query);
        $data->execute();
        $count = $data->rowCount();
        if($count === 1) {
            $active = $db->prepare("UPDATE login SET active = 1 WHERE email = '$email'");
            $active->execute();
            header("Location: ".LOCATION."activated.php");
        }
    }
}

function error_msg($error) {

        if(!empty($_SESSION['error'])) {
            echo "<span class=\"col-xs-5 col-xs-offset-2 error_msg\">".$_SESSION['error']."</span>";
        }
}

function register_user($db) {
    if(empty($_POST) === false){
            
            $username = $_POST['username'];
            $password = md5($_POST['password']);
            $password2 = md5($_POST['password2']);
            $email = $_POST['email'];
            $email_code = md5($email.microtime());
         
            if(empty($username) || empty($password) || empty($password2) || empty($email)) {
                $error = 'You need to fill in all fields!';
            } elseif(username_exists($db) === true) {
                $error  = 'This username is already taken!';
            } elseif(email_exists($db) === true) {
                $error = 'This email is already registered!';
            } elseif($password !== $password2) {
                $error = 'Your passwords are not matching!';
            } /*elseif($password_correct($password) === false) {
                $errors[] = 'Your password needs to have at least 5 characters!';
            }*/
            
            $_SESSION['error'] = $error;
            if(empty($error) === true){
                $query  = "INSERT INTO login (username, password, email, email_code, active) ";
                $query .= "VALUES ('$username', '$password', '$email', '$email_code', 0)";
                $data = $db->prepare($query);
                $data->execute();
                $query2 = "SELECT id FROM login WHERE username = '$username'";
                $data2 = $db->prepare($query2);
                $data2->execute();
                $user_id = $data2->fetch()[0];
                $query3 = "INSERT INTO highscores (highscore, user_id) VALUES (0, '$user_id')";
                $data3 = $db->prepare($query3);
                $data3->execute();
                
                activation_mail($db);
                header('Location: '.LOCATION.'registered.php');
            } 
        }
    }

function username_exists($db) {
    $username = $_POST['username'];
    $query = "SELECT id FROM login WHERE username = '$username'";
    $data = $db->prepare($query);
    $data->execute();
    $count = $data->rowCount();
    if($count >0) {
        return true;
    } else {return false;}
}

function email_exists($db) {
    $email = $_POST['email'];
    $query = "SELECT id FROM login WHERE email = '$email'";
    $data = $db->prepare($query);
    $data->execute();
    $count = $data->rowCount();
    if($count >0) {
        return true;
    } else {return false;}
}

function password_correct($password) {
    if(strlen($password) >= 5) {
        return true;
    } else {return false;}
}

function get_tab_html(){
	for($i=0;$i<16;$i++){
		echo "<li class=\"numbers\" id=\"".$i."\"><span></span></li>";			
	}
}

function login_or_out($el) {
    if($el === "text"){
        if(isset($_SESSION['username'])) {
            echo "logout";
        } else {
            echo "login";
    } 
    }elseif($el === "href") {
        if(isset($_SESSION['username'])) {
            echo "log_out.php";
        }        
    }   
}

function get_target_number(){
	echo mt_rand(1,99);
}

function get_highscore($db, $el){
	//if(isset($_SESSION['username'])){
		$username = $_SESSION['username'];
		$personal_highscore = "SELECT highscore FROM highscores INNER JOIN login";
		$personal_highscore .= " ON highscores.user_id = login.id";
		$personal_highscore .= " WHERE login.username = '$username' AND highscores.highscore != '0'";
		$data = $db->prepare($personal_highscore);
    	$data->execute();
    	$ps_highscore = $data->fetch()[0];

		$overall_highscore ="SELECT highscore FROM highscores WHERE highscore > 0 ORDER BY highscore ASC";
		$data2 = $db->prepare($overall_highscore);
    	$data2->execute();
    	$ov_highscore = $data2->fetch()[0];
    	
		$user_count = "SELECT highscore FROM highscores WHERE highscore != '0'";
		$data3 = $db->prepare($user_count);
    	$data3->execute();
    	$count = $data3->rowCount();

		$ranking = "SELECT highscore FROM highscores WHERE highscore < '$ps_highscore' AND highscore !='0'";
		$data4 = $db->prepare($ranking);
    	$data4->execute();
    	$place = $data4->rowCount();
    	$place = $place+1;

    	if(isset($_SESSION['username']) && $ps_highscore !== NULL){
            //echo "ps highscore: ".gettype($ps_highscore);
        		if($el === 'ps_highscore') {
        			     echo "your highscore: ".get_time_format('ps', $ov_highscore, $ps_highscore);
        		} 	elseif($el === 'ranking') {
        			     echo "your ranking: ".$place."/".$count;
        		}   elseif($el === 'place'){
                        return $place;
                }   elseif($el === 'count'){
                        return $count;
                }   elseif($el === 'ov_highscore') {
                        return $ov_highscore;
                }   elseif($el === 'personal_highscore') {
                        return $ps_highscore;
                }
    	} else {echo "";}
    	if($el === 'ov_highscore') {
    			echo "overall highscore: ".get_time_format('ov', $ov_highscore, $ps_highscore);
    	}
	}

	function get_time_format($el,$ov_highscore,$ps_highscore){
			//global $ov_highscore, $ps_highscore;

			if($el === "ov") {
				$time = $ov_highscore;
			} elseif($el === "ps") {
				$time = $ps_highscore;
			}
          if($time < 10){
              $time_elapsed = "00:00:".$time;
            } elseif($time > 9 && $time < 100) {
                $s1 = floor($time/10);
                $s2 = $time % 10;
                $time_elapsed = "00:0".$s1.":".$s2;
            } elseif($time > 99 && $time < 600){
                $s1 = floor(($time % 100)/10);
                $s2 = ($time % 100) % 10;
                $m2 = floor($time/100);
                $time_elapsed = "00:".$m2.$s1.":".$s2;
            } elseif($time > 599 && $time < 6000){
                $m1 = floor($time / 600);
                $m2 = floor(($time % 600) / 100);
                $s1 = floor((($time % 600) % 100) / 10);
                $s2 = (($time % 600) % 100) % 10;
                $time_elapsed = "0".$m1.":".$m2.$s1.":".$s2;
            }
            return $time_elapsed;
      }

      function recover($db) {
                    $_SESSION['mode'] = $_GET['mode'];   
        
                if(isset($_POST['email']) && !empty($_POST['email'])) {
                        $email = $_POST['email'];
                        if($_SESSION['mode'] === 'password') {
                            change_password($email,$db,'prel');
                        }
                        $result = user_data($db, $_SESSION['mode'],'email', $email);              
                        $message  = "<html><body>";
                        $message .= "Dear Jugglefox player, <br><br>";
                        if($_SESSION['mode'] === 'username') {
                            $message .= "This is your username: ".$result."<br><br>";
                        } elseif($_SESSION['mode'] === 'password') {
                            $message .= "Your new password is: ".$result."<br><br>";
                        }
                        $message .= "Lars from Jugglefox";
                        $headers = 'MIME-Version: 1.0' . "\r\n";
                        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                        mail($email, 'Your Jugglefox username', $message, $headers);
                        header("Location: ".LOCATION."recover.php?success");
                        
                }    
      }

      function change_password($email, $db, $event) {
                    if($event === 'prel') {
                        $generated_password = substr(md5(rand(1000,9999999)),0,8);
                        $query = "UPDATE login SET password = '$generated_password', password_recovered=1 WHERE email = '$email'";
                    } elseif($event === 'new' && isset($_POST['new_password'])) {
                        $new_password = $_POST['new_password'];
                        $current_password = $_POST['current_password'];
                        $username = $_SESSION['username'];
                        $query = "UPDATE login SET password = '$new_password', password_recovered=0 ";
                        $query .="WHERE username = '$username' AND password = '$current_password'";
                    }              
                $data = $db->prepare($query);
                $data->execute();
      }

      function redirect_to_change_password_page($db) {
            $current_file = end(explode('/',$_SERVER['SCRIPT_NAME']));
         
            if(isset($_SESSION['username'])){
                $username = $_SESSION['username'];
                
                $state = user_data($db, 'password_recovered', 'username', $username);

                if($current_file !== 'change_password.php' && $current_file !== 'logout.php' && $state === '1') {
                    echo "success";
                    header('Location: '.LOCATION.'change_password.php');
                }
            }
      }

      function get_html_for_recover_page() {
        if(isset($_GET['mode'])) {
            $out = "<h4>Please enter your email address! ";
            $out .= "We will send you your ".$_GET['mode']." by email.</h4>";
            $out .= "<form action=\"recover.php?mode=".urlencode($_GET['mode'])."\" method=\"post\">";
            $out .= "<input type=\"email\" name=\"email\" size=\"30\">";
            $out .= "<input type=\"submit\" value=\"ok\" class=\"btn btn-success\">";
            $out .= "</form>";
            echo $out;
        } elseif(isset($_GET['success'])) {
            echo "<h3>We've just sent you a new password";
        }
      }

    
