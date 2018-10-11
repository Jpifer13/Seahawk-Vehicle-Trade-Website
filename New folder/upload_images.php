<?php 
session_start();
$folder = $_SESSION['folder'];
$email = $_SESSION['email'];
$admin = $_SESSION['admin'];

require 'includes/header.php';
//this is called after user hits submit button to upload images
if (isset($_POST['submit'])){
	
	if(isset($_FILES['upload'])){
		$temp = 0;
		$allowed = array('image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png');
		if(in_array($_FILES['image']['type'], $allowed)){//uploads thumbnail
			if(move_uploaded_file($_FILES['image']['tmp_name'], "images/thumbs/{$_FILES['image']['name']}")){//puts in thumbs folder
				
			}
		}
		
		if($admin == 0){
		require_once ('../../pdo_config.php');//init connection
		$allowed = array('image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png');
		if(in_array($_FILES['upload']['type'], $allowed)){//uploads image
			if(move_uploaded_file($_FILES['upload']['tmp_name'], "../../uploads/{$_FILES['upload']['name']}")){//puts in uploads folder cause not admin so you cant uplload to site pictures besides myimages
				$name = $_FILES['upload']['name'];
				$type = $_FILES['upload']['type'];
				$sql = "insert into SRV_images(email, filename, filetype, imageID) values (:email, :filename, :filetype, :placehold)";//adds info to SRV_images table in database
				$stmt2= $conn->prepare($sql);
				$stmt2->bindValue(':email', $email);
				$stmt2->bindValue(':filename', $name);
				$stmt2->bindValue(':filetype', $type);
				$stmt2->bindValue(':placehold', $temp);
				$success = $stmt2->execute();
				$_SESSION['filename'] = $name;
				if($success){//checks if worked
					$dets = "null";
	$sql2 = "insert into SRV_trades(imageID, owner_email, filename, details) values (0, :email, :file, :details)";//inserts into SRV_trades table if success
	$stmt3= $conn->prepare($sql);
				$stmt3->bindValue(':email', $email);
				$stmt3->bindValue(':file', $name);
				$stmt3->bindValue(':details', $dets);
				$success = $stmt3->execute();
		echo $email, $name, $dets;
				echo '<p><em>The file '.$_FILES['upload']['name'].' has been uploaded!</em></p?';
				}
				exit();
			}
			if($_SESSION['admin'] == 0){
				echo "yes";
			}
		}else{
			echo '<p class = "error">Please upload a JPEG or PNG image.</p>';
		}
		}
		
		
		if($admin == 1){
		require_once ('../../pdo_config.php');
		$allowed = array('image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png');
		if(in_array($_FILES['upload']['type'], $allowed)){//uploads image
			if(move_uploaded_file($_FILES['upload']['tmp_name'], "images/{$_FILES['upload']['name']}")){//uploads to images folder because these are site images and only admin can add to them
				$name = $_FILES['upload']['name'];
				$type = $_FILES['upload']['type'];
				$sql = "insert into SRV_images(email, filename, filetype, imageID) values (:email, :filename, :filetype, :placehold)";//adds to SRV_images
				$stmt2= $conn->prepare($sql);
				$stmt2->bindValue(':email', $email);
				$stmt2->bindValue(':filename', $name);
				$stmt2->bindValue(':filetype', $type);
				$stmt2->bindValue(':placehold', $temp);
				$success = $stmt2->execute();
				$_SESSION['filename'] = $name;
				if($success){
					$dets = "null";
	$sql2 = "insert into SRV_trades( owner_email, filename, details) values ( :email, :file, :details)";//adds to SRV_trades
	$stmt3= $conn->prepare($sql2);
				$stmt3->bindValue(':email', $email);
				$stmt3->bindValue(':file', $name);
				$stmt3->bindValue(':details', $dets);
				$success = $stmt3->execute();
		echo $email, $name, $dets;
				echo '<main><p><em>The file '.$_FILES['upload']['name'].' has been uploaded!</em></p></main>';
				}
				exit();
			}
			if($_SESSION['admin'] == 0){
				echo "yes";
			}
		}else{
			echo '<p class = "error">Please upload a JPEG or PNG image.</p>';
		}
	}
	
	
	
}

if (file_exists ($_FILES['upload']['tmp_name']) && is_file($_FILES['upload']['tmp_name'])){//unlinks all images that were uploaded
	unlink ($_FILES['upload']['tmp_name']);
	unlink ($_FILES['image']['tmp_name']);
}





}
?>

<main>
	<h2>Image Upload</h2>
	<p>
		<label for = "image">Select thumbnail to upload:</label>
		<input type="file" name="image">
	</p>
	</form>
	
	<form action="upload_images.php" method="post" enctype="multipart/form-data">
	<input type = "hidden" name = "MAX_FILE_SIZE" value = "2097152">
	<fieldset><legend>Select a image to be uploaded:</legend>
	<p><b>File:</b><input type = "file" name = "upload" id = "file"></p>
	</fieldset>
	<div align ="center"><input type = "submit" name = "submit" value = "Submit"></div>
	</form>
	
</main>

<?php include './includes/footer.php'; ?>