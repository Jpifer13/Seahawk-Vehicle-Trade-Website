<?php
require 'includes/header.php';
if (isset($_POST['send'])) {
	$missing = array();
	$errors = array();
	
	$firstName = trim(filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING)); //returns a string
	if (empty($firstName)) 
		$missing[]='firstName';
	
	$lastName = trim(filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING)); //returns a string
	if (empty($lastName)) 
		$missing[]='lastName';
	
	$valid_email = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));	//returns a string or null if empty or false if not valid	
	if (trim($_POST['email']==''))
		$missing[] = 'email';
	elseif (!$valid_email)
		$errors[] = 'email';
	else 
		$email = $valid_email;
	
	
	$phone = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING));
		if (empty($phone))
			$missing[] = 'phone';
		
		
	$password_1 = trim(filter_input(INPUT_POST, 'password_1', FILTER_SANITIZE_STRING));
		if (empty($password_1))
			$missing[] = 'password_1';
		
		
	$password_2 = trim(filter_input(INPUT_POST, 'password_2', FILTER_SANITIZE_STRING));
		if (empty($password_2))
			$missing[] = 'password_2';
		
	// form validation: ensure that the form is correctly filled
	
	if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
		echo "<div align='center'>Passwords do not match</div>";
	}
	if($missing || $errors){
		echo $missing[0], $errors[0];
	}
	if (!$missing && !$errors) {
		try{
			require_once ('../../pdo_config.php'); // Connect to the db.
			$sql = "SELECT * FROM SVT_register WHERE email = :email";
			$stmt = $conn->prepare($sql);
			$stmt->bindValue(':email', $email);
			$stmt->execute();
			$rows = $stmt->rowCount();
			if ($rows==0) {
				try{
					$sql2 = "INSERT into SVT_register (firstName, lastName, email, phone, password) VALUES (:firstName, :lastName, :email, :phone, :password_1)";//adds user to SRV_register table which holds all account holders on site
					$pw = $stmt2= $conn->prepare($sql2);
					$stmt2->bindValue(':firstName', $firstName);
					$stmt2->bindValue(':lastName', $lastName);
					$stmt2->bindValue(':email', $email);
					$stmt2->bindValue(':phone', $phone);
					$stmt2->bindValue(':password_1', password_hash($password_1, PASSWORD_DEFAULT));
					$success = $stmt2->execute();
					echo '<main><h2>Thank you for registering</h2><h3>We have saved your information</h3></main>';
					include 'includes/footer.php'; 
					exit;
				}
			catch (PDOException $e) { 
				echo $e->getMessage(); 
				}
			}
			elseif ($rows==1){ //email found
				$errors[]='duplicate';
				}
		} catch (PDOException $e) { 
			echo $e->getMessage(); 
			
			echo "We are unable to process your request at  this  time. Please try again later.";
			include 'includes/footer.php'; 
			exit;
		}
			echo "Checked: $result";
	}



}
	
?>

<main>
<?php $missing = array(); //creates fields for registration information 
$errors = array();?> 
        <h2>Register</h2>
        <form method="post" action="register.php">
			<fieldset>
				<legend>Register New User</legend>
				<?php if ($missing || $errors) { ?>
				<p class="warning">Please fix the item(s) indicated.</p>
				<?php } ?>
            <p>
                <label for="firstName">First Name: 
				<?php if ($missing && in_array('firstName', $missing)) { ?>
                        <span class="warning">Please enter your first name</span>
                    <?php } ?> </label>
                <input name="firstName" type="text"
				 <?php if (isset($firstName)) {
                    echo 'value="' . htmlspecialchars($firstName) . '"';
                } ?>
				>
            </p>
			
			<p>
                <label for="lastName">Last Name: 
				<?php if ($missing && in_array('lastName', $missing)) { ?>
                        <span class="warning">Please enter your last name</span>
                    <?php } ?> </label>
                <input name="lastName" type="text"
				 <?php if (isset($lastName)) {
                    echo 'value="' . htmlspecialchars($lastName) . '"';
                } ?>
				>
            </p>
			
			
            <p>
                <label for="email">Email: 
				<?php if ($missing && in_array('email', $missing)) { ?>
                        <span class="warning">Please enter your email address</span>
                    <?php } ?>
				<?php if ($errors && in_array('email', $errors)) { ?>
                        <span class="warning">The email address you provided is not valid</span>
                    <?php } ?>
				</label>
                <input name="email" id="email" type="text"
				<?php if (isset($email) && !$errors['email']) {
                    echo 'value="' . htmlspecialchars($email) . '"';
                } ?>>
            </p>
			
			<p>
                <label for="phone">Phone Number: 
				<?php if ($missing && in_array('phone', $missing)) { ?>
                        <span class="warning">Please enter your phone</span>
                    <?php } ?> </label>
                <input name="phone" type="text"
				 <?php if (isset($phone)) {
                    echo 'value="' . htmlspecialchars($phone) . '"';
                } ?>
				>
            </p>
			
			
			<p>
                <label for="password_1">Enter password: 
				<?php if ($missing && in_array('password_1', $missing)) { ?>
                        <span class="warning">Please enter your password</span>
                    <?php } ?> </label>
                <input name="password_1" type="password"
				 <?php if (isset($password_1)) {
                    echo 'value="' . htmlspecialchars($password_1) . '"';
                } ?>
				>
            </p>
			
			<p>
                <label for="password_2">Confirm password: 
				<?php if ($missing && in_array('password_2', $missing)) { ?>
                        <span class="warning">Confirm your password</span>
                    <?php } ?> </label>
                <input name="password_2" type="password"
				 <?php if (isset($password_2)) {
                    echo 'value="' . htmlspecialchars($password_2) . '"';
                } ?>
				>
            </p>
            
            <p>
                <input name="send" type="submit" value="Register">
            </p>
		</fieldset>
        </form>
<?php include 'includes/footer.php'; ?>


