<?php
if (!isset($_SESSION['user_id'])) {
	header('location: view/login.php');
} else if ($_SESSION["user_role"] == 100 || $_SESSION["user_role"] == 1) {
	header('location: view/dashboard.php');
} else {
	header('location: view/home.php');
}
