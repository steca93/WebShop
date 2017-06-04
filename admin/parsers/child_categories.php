<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/WebShop/core/init.php';

	$parentID = (int)$_POST['parentID'];
	$childQurey = $connection->query("SELECT * FROM categories WHERE parent = '$parentID' ORDER BY category");

	ob_start();

 ?>

	<option value=""></option>
	<?php while($child = mysqli_fetch_assoc($childQurey)) : ?>
		<option value="<?php echo $child['id']; ?>"><?php echo $child['category']; ?></option>
	<?php endwhile; ?>

 <?php 
 	echo ob_get_clean();
  ?>