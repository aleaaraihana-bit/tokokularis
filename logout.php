<?php
require_once 'functions.php';
if(user()) log_activity($pdo,'Logout','Keluar dari sistem');
$_SESSION=[];session_destroy();header('Location: login.php');exit;
