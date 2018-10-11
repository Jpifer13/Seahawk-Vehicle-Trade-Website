<?php 
	require 'includes/header.php';
	require_once '../../pdo_config.php';
	function shortTitle ($title){
		$title = substr($title, 0, -4);
		$title = str_replace('_', ' ', $title);
		$title = ucwords($title);
		return $title;
	}
	session_start();
	$email = $_SESSION['email'];
	try{
		$sql = "SELECT * FROM SRV_trades where owner_email = :email";//finds all users images based on who is logged in using their email address
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':email', $email);
		$stmt->execute();
		$rows = $stmt->fetchAll();
		$errorInfo = $conn->errorInfo();
	} catch (PDOException $e) { 
		echo $e->getMessage(); 
		include 'includes/footer.php'; 
		exit;
	   } 
	?>
  <main>
	<h2>Here is everything in your inventory</h2>   
    <table>
        <tr>
            <th>Title</th>
			<th>Image</th>
			<th></th>
        </tr>
        <?php foreach ($rows as $row) { //loop that iterates through user pictures available?>
		<tr>
			<td><?php echo shortTitle($row['filename']); ?></td>
			<td><img src = "images/thumbs/<?php echo $row['filename'];?>">
			<td><form action="img_details.php" method="post">
				  <input type="hidden" name="action" value="details">
				  <input type="hidden" name="imageID" value="<?php echo $row['imageID']; ?>">
				   <input type="hidden" name="qty" value = 1>
				</form>
			</td>	
        </tr>
    <?php } ?>
    </table>
  </main>
<?php include 'includes/footer.php'; ?>