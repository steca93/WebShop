<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/WebShop/core/init.php';
	include 'includes/head.php';
	include 'includes/navigation.php';

	$upit_sveKategorije = "SELECT * FROM categories WHERE parent = 0";
	$result = $connection->query($upit_sveKategorije);
	$errors = array();
	$category = '';
	$post_parent = '';

	//EDIT CATEGORY ==================================================
	if (isset($_GET['edit']) && !empty($_GET['edit'])) {
		$edit_id = (int)$_GET['edit'];
		$edit_id = sanitize($edit_id);
		$upit_edit = "SELECT * FROM categories WHERE id = '$edit_id'";
		$upit_result = $connection->query($upit_edit);
		$edit_category = mysqli_fetch_assoc($upit_result);


	}




	//DELETE CATEGORY ================================================
	if (isset($_GET['delete']) && !empty($_GET['delete'])) {
		$delete_id = (int)$_GET['delete'];
		$delete_id = sanitize($delete_id);

		$upit = "SELECT * FROM categories WHERE id = '$delete_id'";
		$result = $connection->query($upit);
		$category = mysqli_fetch_assoc($result);

		if ($category['parent'] == 0) {
			$upit_parent = "DELETE FROM categories WHERE parent = '$delete_id'";
			$connection->query($upit_parent);
		}

		$upit_delete = "DELETE FROM categories WHERE id = '$delete_id'";
		$connection->query($upit_delete);
		header('Location: categories.php');
	}

	//PROCESS FORM =====================================================
	if (isset($_POST) && !empty($_POST)) {
		$post_parent = sanitize($_POST['parent']);
		$category = sanitize($_POST['category']);
		$upit_child_exists = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent'";
		if (isset($_GET['edit'])) {
			$id = $edit_category['id'];
			$upit_child_exists = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent' AND id != '$id'";
		}
		$fresult = $connection->query($upit_child_exists);
		$count = mysqli_num_rows($fresult);
		//if category is blank ==========================
		if ($category == '') {
			$errors[] .= "The category cannot be left blank.";
		}

		//if exists in the database =============================
		if ($count > 0) {
			$errors[] .= $category . ' already exists. Please chose new category.';
		}

		//Display error or update datebase =========================
		if (!empty($errors)) {
			$display = display_errors($errors); ?>
			<script>
				jQuery('document').ready(function(){
					jQuery('#errors').html('<?php echo $display ?>');
				});
			</script>
		<?php  } else {
			$update_upit = "INSERT INTO categories (category, parent) VALUES ('$category', '$post_parent')";
			if (isset($_GET['edit'])) {
				$update_upit = "UPDATE categories SET category = '$category', parent = '$post_parent' WHERE id = '$edit_id'";
			}
			$connection->query($update_upit);
			header('Location: categories.php');
		}
	}

	$category_value = '';
	$parent_value = 0;
	if (isset($_GET['edit'])) {
		$category_value = $edit_category['category'];
		$parent_value = $edit_category['parent'];
	} else {
		if (isset($_POST)) {
			$category_value = $category;
			$parent_value = $post_parent;
		}
	}

 ?>
 <h2 class="text-center">Categories</h2><hr>
 <div class="row">
 	<div class="col-md-6">
 		<form action="categories.php<?php echo ((isset($_GET['edit']))?'?edit='.$edit_id:'');  ?>" class="form" method="POST">
 		<legend><?php echo ((isset($_GET['edit']))?'Edit':'Add a'); ?> Category</legend>
 		<div id="errors"></div>
 			<div class="form-group">
 				<label for="parent">Parent</label>
 				<select name="parent" id="parent" class="form-control">
 					<option value="0"<?php echo (($parent_value == 0)?'selected="selected"':''); ?>>Parent</option>
 					<?php while($parent = mysqli_fetch_assoc($result)) : ?>
						<option value="<?php echo $parent['id'] ?>"<?php echo (($parent_value == $parent['id'])?'selected="selected"':'') ?>><?php echo $parent['category']; ?></option>

 					<?php endwhile; ?>
 				</select>
 			</div>
 			<div class="form-group">
 				<label for="category">Category</label>
 				<input type="text" class="form-control" id="category" name="category" value="<?php echo $category_value; ?>">
 			</div>
 			<div class="form-group">
 				<input type="submit" value="<?php echo ((isset($_GET['edit']))?'Edit':'Add') ?> Category" class="btn btn-success">
 			</div>
 		</form>
 	</div>



 	<!--=============CATEGORY TABLE======================-->
 	<div class="col-md-6">
 		<table class="table table-bordered">
 			<thead>
 				<th>Categorie</th>
 				<th>Parent</th>
 				<th></th>
 			</thead>
 			<tbody>
 			<?php 
 			$upit_sveKategorije = "SELECT * FROM categories WHERE parent = 0";
			$result = $connection->query($upit_sveKategorije);
 			while($parent = mysqli_fetch_assoc($result)) : 
 				$parent_id = (int)$parent['id'];
 				$upit_child = "SELECT * FROM categories WHERE parent = '$parent_id'";
 				$result_child = $connection->query($upit_child);
 			?>
 				<tr class="bg-primary">
 					<td><?php echo $parent['category']; ?></td>
 					<td>Parent</td>
 					<td>
 						<a href="categories.php?edit=<?php echo $parent['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
 						<a href="categories.php?delete=<?php echo $parent['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
 					</td>
 				</tr>
 				<?php while($child = mysqli_fetch_assoc($result_child)) : ?>
					<tr class="bg-info">
 					<td><?php echo $child['category']; ?></td>
 					<td><?php echo $parent['category']; ?></td>
 					<td>
 						<a href="categories.php?edit=<?php echo $child['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
 						<a href="categories.php?delete=<?php echo $child['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
 					</td>
 				</tr>
				
 				<?php endwhile; ?>
 			<?php endwhile; ?>
 			</tbody>
 		</table>
 	</div>
 </div>

 <?php 
 	include 'includes/footer.php';
  ?>