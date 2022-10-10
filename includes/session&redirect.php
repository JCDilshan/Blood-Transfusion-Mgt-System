<?php
ini_set('session.cookie_lifetime', '86400');
session_start();

if (!isset($_SESSION['user_id'])) {
?>
	<script>
		window.location = "../view/login.php";
	</script>
<?php
}
?>