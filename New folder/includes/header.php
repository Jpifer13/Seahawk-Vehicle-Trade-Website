<?php 
	session_start();
	include 'includes/title.php';
?>	
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Seahawk Vehicle Trade<?php if(isset($title)) {echo "&mdash;$title";} ?></title>
    <link href="styles/seahawkVehicle.css" rel="stylesheet" type="text/css">
</head>

<body>
<header>
    <h1>Seahawk Vehicle Trade</h1>
</header>
<div id="wrapper">
    <?php require 'includes/menu.php'; ?>