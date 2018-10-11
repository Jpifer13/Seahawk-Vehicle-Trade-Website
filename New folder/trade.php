<?php 
	require 'includes/header.php';
	require_once '../../pdo_config.php';
	function shortTitle ($title){
		$title = substr($title, 0, -4);
		$title = str_replace('_', ' ', $title);
		$title = ucwords($title);
		return $title;
	}
	try{
		$sql = "SELECT * FROM SRV_trades";//grabs all vehicles available to trade from database
		$stmt = $conn->prepare($sql);
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
	<h2>Alpacas and whatnot To Rent</h2>
	<h4>Please click the Trade button to view details about trade</h4>      
    <table>
        <tr>
            <th>Title</th>
			<th>Image</th>
			<th></th>
        </tr>
        <?php foreach ($rows as $row) { //loop that prints all images of vehicles and what not to screen?>
		<tr>
			<td><?php echo shortTitle($row['filename']); ?></td>
			<td><img src = "images/thumbs/<?php echo $row['filename'];?>">
			<td><form action="img_details.php" method="post">
				  <input type="hidden" name="action" value="details">
				  <input type="hidden" name="imageID" value="<?php echo $row['imageID']; ?>">
				   <input type="hidden" name="qty" value = 1>
                  <input type="submit" value="Trade">
				</form>
			</td>	
        </tr>
    <?php } ?>
    </table>
  </main>
<?php include 'includes/footer.php'; ?>