<?php
require_once 'reg_conn.php';
session_start();
$first_name = $_SESSION['first_name'];
$email = $_SESSION['email'];

$_SESSION=array();
session_destroy(); //closes current session and logs user out

require 'includes/header.php';
?>
	<main>
	<?php if ( !$_SESSION )  {
			
			$message = "You are now logged out $first_name";
			$message2 = "See you next time";
		} else { 
			$message = 'You have reached this page in error';
			$message2 = 'Please use the menu at the right';	
		}
		echo '<h2>'.$message.'</h2>';
		echo '<h3>'.$message2.'</h3>';
		?>
	</main>
	<?php 
	include ('includes/footer.php'); 
	?>
	