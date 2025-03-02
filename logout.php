<?php
session_start();
session_destroy();
header('Location: landing.php'); // Redirect to home
exit();
?>