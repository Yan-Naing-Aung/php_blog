<?php
	require '../config/config.php';

	$stmt = $pdo->prepare("SELECT image FROM posts WHERE id=".$_GET['id']);
	$stmt->execute();
	$image = $stmt->fetch();

	unlink("../images/".$image['image']);
	
	$stmt = $pdo->prepare("DELETE FROM posts WHERE id=".$_GET['id']);
	$stmt->execute();

	

	header("location:index.php");

?>