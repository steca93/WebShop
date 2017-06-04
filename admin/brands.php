<?php 
	require_once '../core/init.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
	//GET BRANDS FROM DATABASE
	$upit = "SELECT * FROM brand ORDER BY brand";
	$results = $connection->query($upit);
	$errors = array();

	//EDIT BRAND
	if (isset($_GET['edit']) && !empty($_GET['edit'])) {
		$edit_id = (int)$_GET['edit'];
		$edit_id = sanitize($edit_id);
		$upit_edit = "SELECT * FROM brand WHERE id = '$edit_id'";
		$edit_result = $connection->query($upit_edit);
		$eBrand = mysqli_fetch_assoc($edit_result);

	}

	//DELETE BRAND
	if (isset($_GET['delete']) && !empty($_GET['delete'])) {
		$delete_id = (int)$_GET['delete'];
		$delete_id = sanitize($delete_id);
		$upit_delete = "DELETE FROM brand WHERE id = '$delete_id'";
		$connection->query($upit_delete);
		//FUNKCIJA ZA REDIREKTOVANJE NA STRANU
		header('Location: brands.php');
	}

	//AKO ZELIMO DA DODAMO BRAND
	if (isset($_POST['add_submit'])) {
		$brand_upisan = sanitize($_POST['brand']);
		//PROVERI DA LI JE BRAND POLJE PRAZNO
		if ($_POST['brand'] == '') {
			$errors[] .= 'You must enter a brand!';
		}
		//PROVERI DA LI BRAND POSTOJI U BAZI
		$upit2 = "SELECT * FROM brand WHERE brand = '$brand_upisan'";
		if (isset($_GET['edit'])) {
			$upit_edit_1 = "SELECT * FROM brand WHERE brand = '$brand_upisan' AND id != '$edit_id'";
		}
		$result = $connection->query($upit2);
		$count = mysqli_num_rows($result);
		if ($count > 0) {
			$errors[] .= $brand_upisan. ' brand alredy exists. Please chose another brand.';
		}

		//PRIKAZI ERRORE
		if (!empty($errors)) {
			echo display_errors($errors);
		} else {
			//ADD BRAND TO DATABASE
			$upit_add_or_edit = "INSERT INTO brand (brand) VALUE ('$brand_upisan')";
			if (isset($_GET['edit'])) {
				$upit_add_or_edit = "UPDATE brand SET brand = '$brand_upisan' WHERE id = '$edit_id'";
			}
			$connection->query($upit_add_or_edit);
			header('Location: brands.php');
		}
	}

 ?>
<h2 class="text-center">Brands</h2><hr>
<!--BRAND FORM-->

<div class="text-center">
	<form action="brands.php<?php echo ((isset($_GET['edit']))?'?edit='.$edit_id:'')  ?>" method="POST" class="form-inline">
		<div class="form-group">
			<?php 
			$brand_value = '';
			if(isset($_GET['edit'])) {
				$brand_value = $eBrand['brand'];
			} else {
				if (isset($_POST['brand'])) {
					$brand_value = sanitize($_POST['brand']);
				}
			}
			?>
			<label for="brand"><?php echo ((isset($_GET['edit']))?'Edit':'Add a'); ?> Brand:</label>
			<input type="text" name="brand" id="brand" class="form-control" value="<?php echo $brand_value; ?>">
			<?php if(isset($_GET['edit'])) : ?>
				<a href="brands.php" class="btn btn-default">Cancel</a>
			<?php endif; ?>
			<input type="submit" name="add_submit" value="<?php echo ((isset($_GET['edit']))?'Edit':'Add'); ?> Brand" class="btn btn-success">
		</div>
	</form>
</div><hr>
<table class="table table-bordered table-striped table-auto table-condensed"> 
	<thead>
		<th></th>
		<th>Brand</th>
		<th></th>
	</thead>
	<tbody>
		<?php while ($brand = mysqli_fetch_assoc($results)) : ?>
			<tr>
				<td><a href="brands.php?edit=<?php echo $brand['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
				<td><?php echo $brand['brand']; ?></td>
				<td><a href="brands.php?delete=<?php echo $brand['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
			</tr>
		<?php endwhile; ?>
	</tbody>
</table>
 <?php 
 	include 'includes/footer.php';
  ?>