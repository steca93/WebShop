<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/WebShop/core/init.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
	if (isset($_GET['add'])){


	$brandQuery = $connection->query("SELECT * FROM brand ORDER BY brand");
	$parenQuery = $connection->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");

	if ($_POST) {
		$errors = array();
		if (!empty($_POST['sizes'])) {
			$sizeString = sanitize($_POST['sizes']);
			$sizeString = rtrim($sizeString, ','); 
			$sizesArray = explode(',', $sizeString);
			$sArray = array();
			$qArray = array();
			foreach($sizesArray as $ss) {
				$s = explode(':', $ss);
				$sArray[] = $s[0];
				$qArray[] = $s[1];
			}
		} else {
			$sizesArray = array();
		}

		$required = array('title', 'brand', 'price', 'parent', 'child', 'sizes');
		foreach($required as $field){
			if ($_POST[$field] == '') {
				$errors[] = 'All Fields with an Astrisk are required.';
				break;
			}
		}

		if (!empty($_FILES)) {
			//var_dump($_FILES);
			$photo = $_FILES['photo'];
			$name = $photo['name'];
			$nameArray = explode('.', $name);
			$fileName = $nameArray[0];
			$fileExt = $nameArray[1];
			$mime = explode('/', $photo['type']);
			$mimeType = $mime[0];
			$mimeExt = $mime[1];
			$tmpLoc = $photo['tmp_name'];
			$fileSize = $photo['size'];
			$allowed = array('png', 'jpg', 'jpeg', 'gif');
			if ($mimeType != 'image') {
				$errors[] = 'The file must be an image.';
			}
			if (!in_array($fileExt, $allowed)) {
				$errors[] = "The photo must be a png, jpeg, jpg or gif. ";
			}
			if ($fileSize > 15000000) {
				$errors[] = "The file size must be under 15mb.";
			}
		}

		if (!empty($errors)) {
			echo display_errors($errors);
		} else {
			//Upload file and insert into database =========================================
		}
	}
	
?>
	<!--FORM ADD PRODUCT =========================================-->
	<h2 class="text-center">Add a New Product</h2><hr>
	<form action="products.php?add=1" method="POST" enctype="multipart/form-data">
		<div class="form-group col-md-3">
			<label for="title">Title*:</label>
			<input type="text" name="title" class="form-control" id="title" value="<?php echo ((isset($_POST['title']))?sanitize($_POST['title']):'') ?>">
		</div>
		<div class="form-group col-md-3">
			<label for="brand">Brand*:</label>
			<select name="brand" id="brand" class="form-control">
				<option value=""<?php echo ((isset($_POST['brand']) && $_POST['brand']== '')? 'selected' : '');  ?>></option>
				<?php while($brand = mysqli_fetch_assoc($brandQuery)) : ?>
				<option value="<?php echo $brand['id']; ?>"<?php ((isset($_POST['brand']) && $_POST['brand'] == $brand['id'])? 'selected' : ''); ?>><?php echo $brand['brand']; ?></option>
				<?php endwhile; ?>
			</select>
		</div>
		<div class="form-group col-md-3">
			<label for="parent">Parent Category*:</label>
			<select name="parent" id="parent" class="form-control">
				<option value=""<?php echo ((isset($_POST['parent']) && $_POST['parent'] == '')? 'select' : '') ?>></option>
				<?php while($parent = mysqli_fetch_assoc($parenQuery)) : ?>
				<option value="<?php echo $parent['id'] ?>"<?php echo ((isset($_POST['parent']) && $_POST['parent'] == $parent['id'])? 'select' : '') ?>><?php echo $parent['category'] ?></option>
				
				<?php endwhile; ?>
			</select>
		</div>
		<div class="form-group col-md-3">
			<label for="child">Child Category*:</label>
			<select name="child" id="child" class="form-control">
				
			</select>
		</div>
		<div class="form-group col-md-3">
			<label for="price">Price*:</label>
			<input type="text" id="price" name="price" class="form-control" value="<?php echo ((isset($_POST['price']))?sanitize($_POST['price']): '') ?>">
		</div>
		<div class="form-group col-md-3">
			<label for="price">List Price:</label>
			<input type="text" id="list_price" name="list_price" class="form-control" value="<?php echo ((isset($_POST['list_price']))?sanitize($_POST['list_price']): '') ?>">
		</div>
		<div class="form-group col-md-3">
			<label for="">Sizes & Quantity*:</label>
			<button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle'); return false;">Quantity & Sizes</button>
		</div>
		<div class="form-group col-md-3">
			<label for="sizes">Sizes & Qty Preview:</label>
			<input type="text" class="form-control" name="sizes" id="sizes" value="<?php echo ((isset($_POST['sizes']))?$_POST['sizes']: '') ?>" readonly>
		</div>
		<div class="form-group col-md-6">
			<label for="photo">Product Photo:</label>
			<input type="file" name="photo" id="photo" class="form-control">
		</div>
		<div class="form-group col-md-6">
			<label for="description">Description:</label>
			<textarea name="description" class="form-control" id="description" rows="6"><?php echo ((isset($_POST['description']))?sanitize($_POST['description']):'') ?></textarea>
		</div>
		<div class="form-group pull-right">
		<input type="submit" value="Add Product" class="form-control btn btn-success pull-right">
		</div><div class="clearfix"></div>
	</form>

	<!-- Modal ======================================================-->
		<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
		  <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="sizesModalLabel">Size & Quantity</h4>
		      </div>
		      <div class="modal-body">
		      <div class="container-fluid">
		        <?php for($i=1; $i <= 12; $i++) : ?>
					<div class="form-group col-md-4">
						<label for="size<?php echo $i;  ?>">Size</label>
						<input type="text" name="size<?php echo $i;  ?>" id="size<?php echo $i;  ?>" value="<?php echo ((!empty($sArray[$i-1]))?$sArray[$i-1]:'') ?>" class="form-control">
					</div>
					<div class="form-group col-md-2">
						<label for="qty<?php echo $i;  ?>">Quantity</label>
						<input type="number" name="qty<?php echo $i;  ?>" id="qty<?php echo $i;  ?>" value="<?php echo ((!empty($qArray[$i-1]))?$qArray[$i-1]:'') ?>" min="0" class="form-control">
					</div>
		        <?php endfor; ?>
		        </div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle'); return false;">Save changes</button>
		      </div>
		    </div>
		  </div>
		</div>




<?php	} else {
	$upit_za_punjenje_tabele = "SELECT * FROM products WHERE deleted = 0";
	$result_upitPunjTabele = $connection->query($upit_za_punjenje_tabele);
	if (isset($_GET['featured'])) {
		$id = (int)$_GET['id'];
		$featured = (int)$_GET['featured'];
		$upit_featured = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
		$connection->query($upit_featured);
		header('Location: products.php');
	}
 ?>
	<h2 class="text-center">Products</h2>
	<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btnk">Add Product</a>
	<div class="clearfix"></div>
	<hr>

	<table class="table table-bordered table-condensed table-striped">
		<!--TABELA========================================================-->
		<thead>
			<th></th>
			<th>Product</th>
			<th>Price</th>
			<th>Category</th>
			<th>Featured</th>
			<th>Sold</th>
		</thead>
		<tbody>
		<!--WHILE LOOP ZA TABELU=========================================-->
			<?php while($product = mysqli_fetch_assoc($result_upitPunjTabele)) : 
				$childID = $product['categories'];
				$upit_category = "SELECT * FROM categories WHERE id ='$childID'";
				$result_category = $connection->query($upit_category);
				$category_izvuceno = mysqli_fetch_assoc($result_category);
				$parentID = $category_izvuceno['parent'];
				$upit_parent = "SELECT * FROM categories WHERE id = '$parentID'";
				$result_parent = $connection->query($upit_parent);
				$parent_izvuceno = mysqli_fetch_assoc($result_parent);
				$category = $parent_izvuceno['category'].'-'.$category_izvuceno['category'];
			?>
			<tr>
				<td>
					<a href="products.php?edit=<?php echo $product['id'];  ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
					<a href="products.php?delete=<?php echo $product['id'];  ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
				</td>
				<td><?php echo $product['title']; ?></td>
				<td><?php echo money($product['price']); ?></td>
				<td>
				<!--IZVLACENJE KATEGORIJA ZA TABELU=======================-->
					<?php echo $category; ?>
				</td>
				<td>
					<a href="products.php?featured=<?php echo (($product['featured'] == 0)?'1':'0') ?>&id=<?php echo $product['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-<?php echo (($product['featured'] == 1)?'minus':'plus')  ?>"></span></a>
					&nbsp <?php echo (($product['featured'] == 1)?'Featured product':''); ?>
				</td>
				<td></td>
			</tr>


			<?php endwhile; ?>
		</tbody>
	</table>

 <?php }
 	include 'includes/footer.php';
  ?>