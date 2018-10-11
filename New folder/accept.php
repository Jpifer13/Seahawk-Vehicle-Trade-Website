<?php 
require 'includes/header.php';
$owner_email = $_SESSION['email'];
$trader_email = $_SESSION['trader_email'];
$imageID = $_SESSION['imageID'];
//grabs session info on whatever trade you are accepting
try {
			require_once ('../../pdo_config.php');
			$sql = "insert into SRV_accepted(owner, trader, imageID) values (:owner, :trader, :image)";//shows accepted trades and adds to database when accepted
			$stmt = $conn->prepare($sql);
			$stmt->bindValue(':owner', $owner_email);
			$stmt->bindValue(':trader', $trader_email);
			$stmt->bindValue(':image', $imageID);
			$success = $stmt->execute();
			if($success){
			echo '<h3>'."You have successfully accepted this trade".'</h3>';
			}
			
		} 
	    
	   catch (Exception $e) { 
				echo $e->getMessage(); 
			}
			
?>