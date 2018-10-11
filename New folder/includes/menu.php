	<?php 
	$currentPage = basename($_SERVER['SCRIPT_FILENAME']); ?>
	<ul id="nav">
		<li><a href="index.php" <?php if ($currentPage == 'index.php') {echo 'id="here"'; } ?>>Home</a></li>
		<li><a href="random.php" <?php if ($currentPage == 'random.php') {echo 'id="here"'; } ?>>Adventures</a></li>
		<?php if (isset($_SESSION['email'])) { //create menu for registered users ?>
		<li><a href="trade.php" <?php if ($currentPage == 'trade.php') {echo 'id="here"'; } ?>>Browse</a></li>
		<li><a href="upload_images.php" <?php if ($currentPage == 'upload_images.php') {echo 'id="here"'; } ?>>Upload Image</a>
		<li><a href="my_images.php" <?php if ($currentPage == 'my_images.php') {echo 'id="here"'; } ?>>My Images</a>
		<li><a href="logout.php" <?php if ($currentPage == 'logout.php') {echo 'id="here"'; } ?>>Logout</a>
		<?php }
			else { //remaining menu for guests ?>
		<li><a href="alpaca.php" <?php if ($currentPage == 'alpaca.php') {echo 'id="here"'; } ?>>Browse</a></li>
		<li><a href="create_acct.php" <?php if ($currentPage == 'create_acct.php') {echo 'id="here"'; } ?>>Register</a></li>
        <li><a href="login.php" <?php if ($currentPage == 'login.php') {echo 'id="here"'; } ?>>Login</a></li>
		<li><a href = "contact_us.php" <?php if ($currentPage == 'contact_us.php') {echo 'id = "here"';} ?>>Contact</a></li>
		<?php } ?>   
    </ul>