<?php
	require_once 'secure_conn.php';
	require 'includes/header.php';
	
	if (isset($_POST['send'])) {
	$missing = array();
	$errors = array();
	//validates all user input and for any errors or missing data
	$firstname = trim(filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING)); 
	if (empty($firstname)) 
		$missing[]='firstname';
	
	$lastname = trim(filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING)); 
	if (empty($lastname)) 
		$missing[]='lastname';
	
	$phone = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING)); 
	if (empty($phone)) 
		$missing[]='phone';
	
	$valid_email = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
	if (trim($_POST['email']==''))
		$missing[] = 'email';
	elseif (!$valid_email)
		$errors[] = 'email';
	else 
		$email = $valid_email;
	
	$password1 = trim(filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_STRING));
	$password2 = trim(filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING));
	if (empty($password1) || empty($password2)) 
		$missing[]='password';
	elseif ($password1 !== $password2) 
			$errors[] = 'password';
	else $password = $password1;
	//checks if passwords are equivalent
	$accepted = filter_input(INPUT_POST, 'terms');
	if (empty($accepted) || $accepted !='accepted')
		$missing[] = 'accepted';
	
	if (!$missing && !$errors) {//if no errors add info to SRV_register as a new user
	   try{	
		require_once ('../../pdo_config.php'); 
		$sql = "SELECT * FROM SRV_register WHERE email = :email";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':email', $email);
		$stmt->execute();
		$rows = $stmt->rowCount();
		if ($rows==0) { 
		  try{
			$folder = preg_replace("/[^a-zA-Z0-9]/", "", $email);
			$folder = strtolower($folder);
			$admin = 0;
			$sql2 = "INSERT into SRV_register (firstName, lastName, email, password, phone, folder, admin) VALUES (:firstName, :lastName, :email, :password, :phone, :folder, :admin)";//ads to users in database and creates a folder to store images and check if admin
			$pw = $stmt2= $conn->prepare($sql2);
			$stmt2->bindValue(':firstName', $firstname);
			$stmt2->bindValue(':lastName', $lastname);
			$stmt2->bindValue(':email', $email);
			$stmt2->bindValue(':password', password_hash($password1, PASSWORD_DEFAULT));
			$stmt2->bindValue(':phone', $phone);
			$stmt2->bindValue(':folder', $folder);
			$stmt2->bindValue(':admin', $admin);
			$success = $stmt2->execute();
			echo '<main><h2>Thank you for registering</h2><h3>We have saved your information</h3></main>';
			$dirPath = "../../uploads/".$folder;
			mkdir($dirPath, 0777);
			include 'includes/footer.php'; 
			exit;
		  }
		  catch (PDOException $e) { 
			echo $e->getMessage(); 
		  }
		}
		elseif ($rows==1) 
			$errors[]='duplicate';
		else { 
			echo "We are unable to process your request at  this  time. Please try again later.";
			include 'includes/footer.php'; 
			exit;
		}
	   } catch (PDOException $e) { 
			echo $e->getMessage(); 
			include 'includes/footer.php'; 
			exit;
	   } 
	   
	}
}?>

	<main>
        <h2>Create Account</h2>
         <form method="post" action="create_acct.php">
			<fieldset>
				<legend>Please Register:</legend>
				<?php if ($missing || $errors) { 
					if($errors && in_array('duplicate', $errors)){?>
						<p class="warning">Email: The email address you entered already exists in the database.
						Please enter another email address or login using the menu to the left.</p>
					<?php } 
					else{?>
					<p class="warning">Please fix the item(s) indicated.</p>
				<?php }}?>	
            <p>
                <label for="fn">First Name: 
				<?php if ($missing && in_array('firstname', $missing)) { ?>
                        <span class="warning">Please enter your first name</span>
                    <?php } ?> </label>
                <input name="firstname" id="fn" type="text"
				 <?php if (isset($firstname)) {
                    echo 'value="' . htmlspecialchars($firstname) . '"';
                } ?>
				>
            </p>
			<p>
                <label for="ln">Last Name: 
				<?php if ($missing && in_array('lastname', $missing)) { ?>
                        <span class="warning">Please enter your last name</span>
                    <?php } ?> </label>
                <input name="lastname" id="ln" type="text"
				 <?php if (isset($lastname)) {
                    echo 'value="' . htmlspecialchars($lastname) . '"';
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
                <label for="phone">Phone: 
				<?php if ($missing && in_array('phone', $missing)) { ?>
                        <span class="warning">Please enter your phone number</span>
                    <?php } ?> </label>
                <input name="phone" id="fn" type="text"
				 <?php if (isset($phone)) {
                    echo 'value="' . htmlspecialchars($phone) . '"';
                } ?>
				>
            </p>
			<p>
				<?php if ($errors && in_array('password', $errors)) { ?>
                        <span class="warning">The entered passwords do not match. Please try again.</span>
                    <?php } ?> 
                <label for="pw1">Password: 
				
				<?php if ($missing && in_array('password', $missing)) { ?>
                        <span class="warning">Please enter a password</span>
                    <?php } ?> </label>
                <input name="password1" id="pw1" type="password">
            </p>
			<p>
                <label for="pw2">Confirm Password: 
				<?php if ($missing && in_array('password', $missing)) { ?>
                        <span class="warning">Please confirm the password</span>
                    <?php } ?> </label>
                <input name="password2" id="pw2" type="password">
            </p>
         
            <p>
			<?php if ($missing && in_array('accepted', $missing)) { ?>
                        <span class="warning">You must agree to the terms</span><br>
                    <?php } ?>
                <input type="checkbox" name="terms" value="accepted" id="terms"
				     <?php if ($accepted) {
                                echo 'checked';
                            } ?>
				>
                <label for="terms">I accept the terms of using this website</label>
            </p>
            <p>
                <input name="send" type="submit" value="Register">
            </p>
		</fieldset>
        </form>
	</main>
<?php include './includes/footer.php'; ?>
