<?php
if (isset($_POST['send'])) {
	$missing = array();
	$errors = array();
	//validates all input information and checks if any info is missing
	$valid_email = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));	
	if (trim($_POST['email']=='')|| (!$valid_email))
		$missing[] = 'email';
	else
		$email = $valid_email;
	
	$password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
	
	if (empty($password))
		$missing[]='password';
	while (!$missing && !$errors){ 
	   try {
		require_once ('../../pdo_config.php');
		$sql = "SELECT firstName, email, password, folder, admin FROM SRV_register WHERE email = :email";//searches for account based on info that was input
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':email', $email);
		$stmt->execute();
		$rows = $stmt->rowCount();
		if ($rows==0)  
			$errors[] = 'email';
		else {
			$result = $stmt->fetch();
			if ($password == password_verify($password, $result['password'])) { //checs to make sure input password matches what is in account
				$firstName = $result['firstName'];
				$folder = $result['folder'];
				$admin = $result['admin'];
				session_start();
				$_SESSION['first_name'] = $firstName;
				$_SESSION['email'] = $email;
				$_SESSION['folder'] = $folder;
				$_SESSION['admin'] = $admin;
				header('Location: logged_in.php'); 
				
				exit;
			}
			else {
				$errors[]='password';
			}
		} 
	   }  
	   catch (Exception $e) { 
				echo $e->getMessage(); 
			}
	}
}
require 'includes/header.php';
?>
	<main>
        <h2>Log In</h2>
        <form method="post" action="login.php">
			<fieldset>
				<legend>Registered Users Login</legend>
				<?php if ($missing || $errors) { ?>
				<p class="warning">Please fix the item(s) indicated.</p>
				<?php } ?>
            <p>
                <label for="email">Please enter your email address:
				
				<?php if ($missing && in_array('email', $missing)) { ?>
                        <span class="warning">An email address is required</span>
                    <?php } ?>
					<?php if ($errors && in_array('email', $errors)) { ?>
                        <span class="warning"><br>The email address you provided is not associated with an account<br>
						Please try another email address or use the link to the left to Register</span>
                    <?php } ?>
				</label>
                <input name="email" id="email" type="text"
				<?php if (isset($email) && !$errors['email']) {
                    echo 'value="' . htmlspecialchars($email) . '"';
                } ?>>
            </p>
			<p>
				<?php if ($errors && in_array('password', $errors)) { ?>
                        <span class="warning">The password supplied does not match the password for this email address. Please try again.</span>
                    <?php } ?>
                <label for="pw">Password: 
				
				<?php if ($missing && in_array('password', $missing)) { ?>
                        <span class="warning">Please enter a password</span>
                    <?php } ?> </label>
                <input name="password" id="pw" type="password">
            </p>
			
            <p>
                <input name="send" type="submit" value="Login">
            </p>
		</fieldset>
        </form>
	</main>
<?php include 'includes/footer.php'; ?>
