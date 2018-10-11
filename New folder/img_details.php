<?php
	require 'includes/header.php';
	require_once '../../pdo_config.php';
	echo '<main>';
	
	function shortTitle ($title){
		$title = substr($title, 0, -4);
		$title = str_replace('_', ' ', $title);
		$title = ucwords($title);
		return $title;
	}//validates imageID
	$imgID = filter_input(INPUT_POST, 'imageID', FILTER_VALIDATE_INT);
			session_start();
			$_SESSION['imageID'] = $imgID;//uploads imageID to the session
	try{
	   $getDetails= "SELECT * FROM SRV_trades WHERE imageID = :imgID";//pulls picture from SRV_trades based on input data
		$stmt = $conn->prepare($getDetails);	
		$stmt->bindValue(':imgID', $imgID);
		$stmt->execute();
		$rows = $stmt->rowCount();
		if ($rows == 1) { 
			$item = $stmt->fetch();
			$imgID = $item['image_id'];
			$fileName = $item['filename'];
			$owner = $item['owner_email'];
			$imgDetails = $item['details'];

			
			//shos image as well as the current owner and details about image?>
			
			
			
			<h2>Purchase <?php echo shortTitle($fileName);?>:</h2>					
			<p><img src="images/<?php echo $fileName; ?>" alt="<?php echo $fileName; ?>"></p>
			<h3><strong>Description:</strong></h3>
			<h4><?php echo $imgTitle; ?></h4>
			<h4><?php echo $imgDetails; ?></h4>
			<h3><strong>Current Owner Email:</strong?</h3>
			<h4><?php echo $owner; ?></h4>
			<h4><strong>Trade? </strong>
			<form style="display:inline;" action="contactOwner.php" method="post">
			  
			  <input type="submit" value="Contact Owner">
			</form></h4>						
		<?php }
		else {
			echo "You have reached this page in error";
			include 'includes/footer.php';
			exit;
		}
	} catch (PDOException $e) { 
		echo $e->getMessage(); 
		include 'includes/footer.php'; 
		exit;
	   } 
echo '</main>';
include 'includes/footer.php'; ?>