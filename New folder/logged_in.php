<?php 
require 'includes/header.php';
$first_name = $_SESSION['first_name'];
$email = $_SESSION['email'];
?>
	<main>
	<?php if ( $_SESSION )  {//creates follow up page after log in and shows if the user has any trade requests
			
			$message = "Welcome back $first_name";
			$message2 = "You are now logged in";
			
		
			
		} else { 
			$message = 'You have reached this page in error';
			$message2 = 'Please use the menu at the right';	
		}
		echo '<h2>'.$message.'</h2>';
		echo '<h3>'.$message2.'</h3>';
		try {
			require_once ('../../pdo_config.php');
			$sql = "select * from SRV_tradeRequests where owner_email = :email";//pulls any request to trade from database and lets user know
			$stmt = $conn->prepare($sql);
			$stmt->bindValue(':email', $email);
			$stmt->execute();
			$rows = $stmt->rowCount();
			if ($rows==0){  
			$messages = 'No new messages';
			}
			else {
				
			$rows = $stmt->fetchAll();
			?><table>
			<?php foreach ($rows as $row) { //loop that will pull all request and give user option to act upon requests
			$_SESSION['trader_email'] = $row['trader_email'];
			?>
			<tr>
			<td><?php echo $row['trader_name'].' requests to trade '.$row['imageID'].' and has these comments '.$row['comments']; ?></td>
			<td><form action="accept.php" method="post">
				  <input type="hidden" name="imageID" value="<?php echo $row['imageID'];$_SESSION['imageID'] = $row['imageID']; ?>">
                  <input type="submit" value="Trade">
				</form>
			</td>
			</td>	
        </tr>
			
			</table>
			<?php }
		} 
		}
	    
	   catch (Exception $e) { 
				echo $e->getMessage(); 
			}
			
		
		?>
	</main>
	<?php 
	include ('./includes/footer.php'); 
	?>
	