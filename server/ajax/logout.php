<?php
	session_start();
	unset($_SESSION['vkId']);
	header('Location: index.html');

?>