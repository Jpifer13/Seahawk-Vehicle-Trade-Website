<?php
if (isset($_GET['send'])) {
require 'includes/header.php';
session_start();//starts session and pulls user email, name, and ImageID of current session
$firstName = $_SESSION['first_name'];
$email = $_SESSION['email'];
$imageID = $_SESSION['imageID'];
//validates comments to owner
$comments = trim(filter_input(INPUT_GET, 'comments')); 
	$comments = nl2br($comments, false); 
	if (empty($comments))
		$missing[] = 'comments';
	
	
	try {
		require_once ('../../pdo_config.php');
		$sql = "select owner_email from SRV_trades where imageID = :image";//grabs the owners email from selected image
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':image', $imageID);
		$stmt->execute();
		$rows = $stmt->rowCount();
		$result = $stmt->fetch();
		
		$owner_email = $result['owner_email'];
		$_SESSION['owner_email'] = $owner_email;
		
		} 
	    
	   catch (Exception $e) { 
				echo $e->getMessage(); 
			}
	try {
		require_once ('../../pdo_config.php');
		$sql = "insert into SRV_tradeRequests (owner_email, trader_email, trader_name, imageID, comments) values (:owner, :trader, :name, :image, :comments)";//creates a new trade request based on selected image
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':owner', $owner_email);
		$stmt->bindValue(':trader', $email);
		$stmt->bindValue(':name', $firstName);
		$stmt->bindValue(':image', $imageID);
		$stmt->bindValue(':comments', $comments);
		$success = $stmt->execute();
		if($success){
			echo "<main><h2>Thank you for requesting a trade, $firstName</h2><h3>We have saved your request</h3></main>";
			}
		} 
	    
	   catch (Exception $e) { 
				echo $e->getMessage(); 
			}
	include 'includes/footer.php'; 
	exit;
}


require 'includes/header.php';


?>

<main>
        <h2>Seahawk Rental</h2>
        <form method="get" action="contactOwner.php">
			<fieldset>
				<legend>Enter your suggested trade</legend>
            <p>
                <label for="comments">Trade: </label>
				<?php if ($missing && in_array('comments', $missing)) { ?>
					<span class="warning">Please enter a request:</span>
                    <?php } ?>
				<textarea name="comments" id="comments"><?php if(!empty($comments)) echo $comments; ?></textarea>
            </p>
            
            <p>
                <input name="send" type="submit" value="Send message">
            </p>
		</fieldset>
        </form>
		</main>
<?php include './includes/footer.php'; ?>