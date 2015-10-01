<?php
ob_start();
session_start();
include("functions.php"); 
include("config.php");
include("head.php");
error_reporting(0);
$errors = array();
register_user($db);
?>
		<div id="signup_form" class="container">
			<form action="" method="post">
				<ul class="col-xs-12 row" id="signup_fields">
					<?php error_msg(); ?>
					<li class="col-xs-4 col-xs-offset-2 signup_fields">please choose a username</li>
					<li class="col-xs-6 signup_fields"><input type="text" id="signup_username" name="username"></li>
					<li class="col-xs-4 col-xs-offset-2 signup_fields">please choose a password</li>
					<li class="col-xs-6 signup_fields"><input type="password" id="signup_password" name="password"></li>
					<li class="col-xs-4 col-xs-offset-2 signup_fields">please repeat your password</li>
					<li class="col-xs-6 signup_fields"><input type="password" id="password2" name="password2"></li>
					<li class="col-xs-4 col-xs-offset-2 signup_fields">please type in your e-mail address</li>
					<li class="col-xs-6 signup_fields"><input type="text" id="signup_email" name="email"></li>
					<li class="col-xs-6 col-xs-offset-2 signup_fields"><input type="submit" class="btn btn-success" value="sign up"></li>
				</ul>
			</form>
		</div>
	</body>
</html>