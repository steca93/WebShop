<?php 
	require_once 'core/init.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
	include 'includes/headerFull.php';
	include 'includes/leftSideBar.php';

	$upit = "SELECT * FROM products WHERE featured = 1";
	$featured = $connection->query($upit);
 ?>
	<!--TOP NAV BAR-->
	
	
	<!--HEADER-->
	
	

	<!--MAIN CONTENT-->
	<div class="col-md-8">
		<div class="row">
			<h2 class="text-center">Featured Product</h2>
			<?php while ($product = mysqli_fetch_assoc($featured)) : ?>
				<div class="col-md-3">
					<h4><?php echo $product['title']; ?></h4>
					<img src="<?php echo $product['image']; ?>" alt="<?php echo $product['title'] ?>" class="img-tumb" />
					<p class="list-price text-danger">List Price <s>€ <?php  echo $product['list_price']; ?></s></p>
					<p class="price">Our Price: € <?php echo $product['price']; ?></p>
					<button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?php echo $product['id']; ?>);">Details</button>
				</div>
		<?php endwhile; ?>
		</div>
	</div>



	<?php 
		//include 'includes/detailsModal.php';
		include 'includes/rightSideBar.php';
		include 'includes/footer.php';
	 ?>
	