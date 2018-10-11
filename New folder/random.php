<?php 
  try{	//grabs random image from available vehicles in database
	require_once('../../pdo_config.php');
	$sql = 'SELECT COUNT(imageID) FROM SRV_trades';
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$numrows = $stmt->fetchColumn();
	$randomRow = mt_rand(0, $numrows-1);//grabs random number based on how many rows in SRV_trades
	$sql2 = "SELECT filename, details, owner_email FROM SRV_trades LIMIT $randomRow, 1";//uses that number to pick a picture 
	$stmt2 = $conn->prepare($sql2);
	$stmt2->bindValue(':randomRow', $randomRow);
	$stmt2->execute();
	$result = $stmt2->fetch();
	$image = $result['filename'];
	$details = $result['details'];
	$owner = $result['owner_email'];
	$imagePath = 'images/'.$image;//creates image path of picture
	if (file_exists($imagePath)){
		$imageSize=getimagesize($imagePath);
		}
  } catch (PDOException $e) { 
		echo $e->getMessage(); 
		exit();
	}
	require 'includes/header.php';
//takes random picture and prints it the screen along with details about it?>
<main>
<h2>Random Trades:</h2>					
			<p><img src="images/<?php echo $image; ?>" alt="<?php echo $image; ?>"></p>
			<h3><strong>Description:</strong></h3>
			<h4><?php echo $details; ?></h4>
			<h3><strong>Current Owner Email:</strong?</h3>
			<h4><?php echo $owner; ?></h4>
			  
			  
		</main>	
			
<?php include 'includes/footer.php'; ?>


