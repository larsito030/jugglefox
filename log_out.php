<?php
ob_start();
session_start();
session_destroy();
include("config.php");
header('Location: jugglefox.php');
//echo "logout";


