<?php 
  if (isset($_GET['send'])) {
	require 'includes/header.php';
	$missing = array();
	$errors = array();
	//validates user input and checks for missing info or mistakes
	$name = trim(filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRING)); 
	if (empty($name)) 
		$missing[]='name';
	
	$valid_email = trim(filter_input(INPUT_GET, 'email', FILTER_VALIDATE_EMAIL));		
	if (empty(trim($_GET['email'])))
		$missing[] = 'email';
	elseif (!$valid_email)
		$errors[] = 'email';
	else $email = $valid_email;
	
	$phone = trim(filter_input(INPUT_GET, 'phone', FILTER_SANITIZE_STRING)); 
	if (empty($phone)) 
		$missing[]='phone';
		
			
	$comments = trim(filter_input(INPUT_GET, 'comments')); 
	$comments = nl2br($comments, false); 
	if (empty($comments))
		$missing[] = 'comments';
		
	
	$terms = filter_input(INPUT_GET, 'terms');
	if(!isset($terms) || $terms !='accepted')
		$missing[] = 'terms';
	
	if (!$missing && !$errors) {//no errors or missing data then prints user input
		echo "<main><h2>Thank you for contacting us</h2><br>
			<h4>We have received the following information:</h4>
			Name: $name<br>
			Email: $email<br>
			Phone: $phone<br>
			Comments: $comments<br>
			You accepted our terms of use<br><br>
			</main>";
			
			$tempName = explode(" ",$name);
			$firstName= $tempName[0];
			if (!empty($tempName[1])) {
				$lastName = $tempName[1];
			} else {
				$lastName = null;
			}
			
			$subscribe = filter_input(INPUT_GET, 'subscribe');
			if ($subscribe == 'yes'){
				$subscribe = 1;}
			elseif ($subscribe =="no"){
				$subscribe = 0;}
			else{
				$missing[]='subscribe';}
				
			try{
			require_once ('../../pdo_config.php'); 
			$sql = "INSERT into SRV_contacts (firstName, lastName, emailAddr, phone, comments) VALUES (:firstName, :lastName, :email, :phone, :comments)";//adds contact info to database 
			
			$stmt= $conn->prepare($sql);
			$stmt->bindValue(':firstName', $firstName);
			$stmt->bindValue(':lastName', $lastName);
			$stmt->bindValue(':email', $email);
			$stmt->bindValue(':phone', $phone);
			$stmt->bindValue(':comments', $comments);
			$success = $stmt->execute();
			if($success){
			echo "<main><h2>Thank you for contacting us, $firstName</h2><h3>We have saved your information</h3></main>";
			}
		} catch (PDOException $e) { 
			echo $e->getMessage(); 
		}

		
	include 'includes/footer.php'; 
	exit;
	}
	}
	require 'includes/header.php';
?>

	<main>
        <h2>Seahawk Rental</h2>
        <form method="get" action="contact_us.php">
			<fieldset>
				<legend>Contact Us</legend>
				<?php if ($missing || $errors) { ?>
				<p class="warning">Please fix the item(s) indicated.</p>
				<?php } ?>
            <p>
                <label for="name">Name: 
				<?php if ($missing && in_array('name', $missing)) { ?>
                        <span class="warning">Please enter your name</span>
                    <?php } ?> </label>
                <input name="name" type="text"
				 <?php if (isset($name)) {
                    echo 'value="' . htmlspecialchars($name) . '"';
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
                    <?php } ?>
				<?php if ($errors && in_array('phone', $errors)) { ?>
                        <span class="warning">The phone you provided is not valid</span>
                    <?php } ?>
				</label>
                <input name="phone" id="phone" type="text"
				<?php if (isset($phone) && !$errors['phone']) {
                    echo 'value="' . htmlspecialchars($phone) . '"';
                } ?>>
            </p>
            <p>
                <label for="comments">Comments: </label>
				<?php if ($missing && in_array('comments', $missing)) { ?>
					<span class="warning">Please enter a comment:</span>
                    <?php } ?>
				<textarea name="comments" id="comments"><?php if(!empty($comments)) echo $comments; ?></textarea>
            </p>
            <p>
				<?php if ($missing && in_array('terms', $missing)) { ?>
                        <span class="warning">Please accept our terms of use:</span><br>
                    <?php } ?>
                <input type="checkbox" name="terms" value="accepted" id="terms"
				<?php if(isset($terms) && $terms == 'accepted') echo ' checked'; ?>
				
                <label for="terms">I accept the terms of using this website</label>
            </p>
            <p>
                <input name="send" type="submit" value="Send message">
            </p>
		</fieldset>
        </form>
		</main>
<?php include './includes/footer.php'; ?>
