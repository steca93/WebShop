<?php 
	$upit = "SELECT * FROM categories WHERE parent = 0";
	$parentQuery = $connection->query($upit);
 ?>

<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<a href="index.php" class="navbar-brand">DIN DESING</a>
			<ul class="nav navbar-nav">
			<?php while ($parent = mysqli_fetch_assoc($parentQuery)) : {
				# code...
			} ?>

				<?php $parent_id = $parent['id'];

						$upit2 = "SELECT * FROM categories  WHERE parent = '$parent_id'";
						$childQuery = $connection->query($upit2);

				?>
				<!--MENU ITEMS-->
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['category'] ?><span class="carret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<?php while ($child = mysqli_fetch_assoc($childQuery)) : ?>
						<li><a href="#"><?php echo $child['category']; ?></a></li>
					<?php endwhile; ?>
					</ul>
				</li>
			<?php endwhile; ?>
			</ul>
		</div>
	</nav>